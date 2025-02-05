<?php

namespace api_geoquizz\core\repositoryInterface;

use api_geoquizz\core\domain\entities\geoquizz\Game;

interface GameRepositoryInterface
{

    public function save(Game $game): void;

    public function findById(string $id): ?Game;
}