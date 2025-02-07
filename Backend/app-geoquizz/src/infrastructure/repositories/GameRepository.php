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
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Query\QueryException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\ConnectionException;

class GameRepository implements GameRepositoryInterface
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Sauvegarde un jeu en base de données.
     * @throws RepositoryEntityConflictException
     * @throws RepositoryEntityValidationException
     * @throws RepositoryConnectionException
     * @throws RepositoryException
     */
    public function save(Game $game): void
    {
        try {
            if (!$this->entityManager->contains($game)) {
                try {
                    $game = $this->entityManager->merge($game);
                } catch (ORMException $e) {
                    throw new RepositoryException("Erreur lors de la fusion de l'entité Game.", 500, $e);
                }
            }
            $this->entityManager->persist($game);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new RepositoryEntityConflictException("Ce jeu existe déjà.", 409, $e);
        } catch (ForeignKeyConstraintViolationException $e) {
            throw new RepositoryEntityValidationException("Violation de contrainte de clé étrangère.", 400, $e);
        } catch (ConnectionException $e) {
            throw new RepositoryConnectionException("Erreur de connexion à la base de données.", 503, $e);
        } catch (ORMException $e) {
            throw new RepositoryException("Erreur lors de la sauvegarde du jeu.", 500, $e);
        }
    }

    /**
     * Recherche un jeu par son ID.
     * @throws RepositoryEntityNotFoundException
     * @throws RepositoryException
     */
    public function findById(string $id): ?Game {
        try {
            $game = $this->entityManager->find(Game::class, $id);
        } catch (\Exception $e) {
            throw new RepositoryException(
                sprintf("Erreur lors de la récupération du jeu (ID: %s): %s", $id, $e->getMessage()),
                $e->getCode(),
                $e
            );
        }

        if ($game === null) {
            throw new RepositoryEntityNotFoundException("Le jeu avec l'ID {$id} est introuvable.");
        }

        return $game;
    }

    public function getHighestScoreBySerieForUser(string $serieId, string $userId): int
    {
        try {
            $query = $this->entityManager->createQueryBuilder()
                ->select('MAX(g.score)')
                ->from(Game::class, 'g')
                ->Where('g.serieId = :serieId')
                ->andWhere('g.userId = :userId')
                ->setParameter('userId', $userId)
                ->setParameter('serieId', $serieId)
                ->getQuery();

            return (int)$query->getSingleScalarResult();
        } catch (\Doctrine\DBAL\Exception\ConnectionException $e) {
            throw new RepositoryConnectionException("Erreur de connexion à la base de données", 503, $e);
        } catch (\Doctrine\ORM\Exception\ORMException $e) {
            throw new RepositoryException("Erreur lors de la récupération du score", 500, $e);
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * Récupère tous les jeux.
     * @throws RepositoryException
     */
    public function findAll(): array {
        try {
            return $this->entityManager->createQuery("SELECT g FROM api_geoquizz\core\domain\entities\geoquizz\Game g")
                ->getResult();
        } catch (QueryException $e) {
            throw new RepositoryException("Erreur lors de la récupération des jeux.", 500, $e);
        }
    }
}
