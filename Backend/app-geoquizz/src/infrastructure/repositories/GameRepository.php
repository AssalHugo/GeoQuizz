<?php

namespace api_geoquizz\infrastructure\repositories;

use api_geoquizz\core\domain\entities\geoquizz\Game;
use api_geoquizz\core\repositoryInterface\GameRepositoryInterface;
use api_geoquizz\core\repositoryInterface\RepositoryEntityNotFoundException;
use api_geoquizz\core\repositoryInterface\RepositoryEntityConflictException;
use api_geoquizz\core\repositoryInterface\RepositoryEntityValidationException;
use api_geoquizz\core\repositoryInterface\RepositoryConnectionException;
use api_geoquizz\core\repositoryInterface\RepositoryException;
use Doctrine\ORM\EntityManager;

class GameRepository implements GameRepositoryInterface {
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function save(Game $game): void {
        try {
            if (!$this->entityManager->contains($game)) {
                $game = $this->entityManager->merge($game);
            }
            $this->entityManager->persist($game);
            $this->entityManager->flush();
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            throw new RepositoryEntityConflictException("Une entité avec ces données existe déjà", 409, $e);
        } catch (\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException $e) {
            throw new RepositoryEntityValidationException("Référence invalide", 400, $e);
        } catch (\Doctrine\DBAL\Exception\ConnectionException $e) {
            throw new RepositoryConnectionException("Erreur de connexion à la base de données", 503, $e);
        } catch (\Doctrine\ORM\Exception\ORMException $e) {
            throw new RepositoryException("Erreur lors de la sauvegarde", 500, $e);
        }
    }

    public function findById(string $id): ?Game {
        try {
            $game = $this->entityManager->find(Game::class, $id);
        } catch (\Exception $e) {
            $errorMessage = sprintf(
                "Erreur lors de la récupération du jeu (ID: %s): %s \nTrace: %s",
                $id,
                $e->getMessage(),
                $e->getTraceAsString()
            );

            throw new \Exception($errorMessage, $e->getCode(), $e);
        }
        if ($game == null) {
            throw new RepositoryEntityNotFoundException('Game not found (ID: ' . $id . ')');
        }

        return $game;
    }
}