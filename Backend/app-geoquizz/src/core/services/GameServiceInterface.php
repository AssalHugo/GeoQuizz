<?php

namespace api_geoquizz\core\services;

use api_geoquizz\core\domain\entities\geoquizz\Game;
use api_geoquizz\core\domain\entities\seriesDirectus\Photo;

interface GameServiceInterface {

    public function createGame(string $serie, string $userId): Game;
    public function isFinished(Game $game):bool;
    public function startGame(Game $game): void;
    public function getCurrentPhoto(Game $game): ?Photo;
    public function calculateScore(Game $game, float $distance, float $responseTime): int;
    public function updateGameProgress(Game $game, float $latitude, float $longitude): int;
    public function endGame(Game $game): void;
    public function saveGameResult(Game $game): bool;
    public function getGamePhotos(Game $game): array;
    public function getGameById(string $gameId): ?Game;
    public function getGameState(Game $game): string;


}