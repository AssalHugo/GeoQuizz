<?php

namespace api_geoquizz\core\services;

use api_geoquizz\core\domain\entities\geoquizz\Game;
use api_geoquizz\core\domain\entities\seriesDirectus\Photo;
use api_geoquizz\core\dto\GameDTO;

interface GameServiceInterface {

    public function createGame(string $serie, string $userId): GameDTO;
    public function isFinished(GameDTO $game):bool;
    public function startGame(GameDTO $game): void;
    public function getCurrentPhoto(GameDTO $game): ?Photo;
    public function calculateScore(GameDTO $game, float $distance, float $responseTime): int;
    public function updateGameProgress(GameDTO $game, float $latitude, float $longitude): int;
    public function endGame(GameDTO $game): void;
    public function saveGameResult(GameDTO $game): bool;
    public function getGamePhotos(GameDTO $game): array;
    public function getGameById(string $gameId): ?GameDTO;
    public function getGameState(GameDTO $game): string;
    public function getHighestScoreBySerieForUser(string $serieId, string $userId): int;
    public function getGamesByUser(string $userId): array;


}