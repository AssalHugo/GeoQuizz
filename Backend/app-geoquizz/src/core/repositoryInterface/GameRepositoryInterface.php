<?php

namespace api_geoquizz\core\repositoryInterface;

use api_geoquizz\core\domain\entities\geoquizz\Game;

interface GameRepositoryInterface
{

    public function save(Game $game): void;

    public function findById(string $id): ?Game;
    public function findAll(): array;

    public function getHighestScoreBySerieForUser(string $serieId, string $userId) : int|array;

    public function getGamesByUser(string $userId): array;
}