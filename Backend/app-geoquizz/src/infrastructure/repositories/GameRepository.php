<?php

namespace api_geoquizz\infrastructure\repositories;

use api_geoquizz\core\domain\entities\geoquizz\Game;
use api_geoquizz\core\repositoryInterface\GameRepositoryInterface;
use Doctrine\ORM\EntityManager;

class GameRepository implements GameRepositoryInterface {
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function save(Game $game): void {
        $this->entityManager->persist($game);
        $this->entityManager->flush();
    }

    public function findById(string $id): ?Game {
        return $this->entityManager->find(Game::class, $id);
    }
}