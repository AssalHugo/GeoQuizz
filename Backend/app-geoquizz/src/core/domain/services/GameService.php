<?php

namespace api_geoquizz\core\domain\services;

use api_geoquizz\core\domain\entities\Game;
use  api_geoquizz\core\domain\repositories\GameRepository;

class GameService {
    private GameRepository $gameRepository;

    public function __construct(GameRepository $gameRepository) {
        $this->gameRepository = $gameRepository;
    }

    public function createGame(string $serie, string $userId): Game {
        $game = new Game();
        $game->setUserId($userId);
        $game->setSerie($serie);
        $game->setStatus('CREATED');
        $this->gameRepository->save($game);
        return $game;
    }
}