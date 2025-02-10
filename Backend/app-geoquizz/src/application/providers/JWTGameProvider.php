<?php

namespace api_geoquizz\application\provider;

use api_geoquizz\application\providers\JWTGameProviderInterface;
use api_geoquizz\application\providers\JWTGameManager;

class JWTGameProvider implements JWTGameProviderInterface
{
    private JWTGameManager $jwtGameManager;

    public function __construct(JWTGameManager $jwtGameManager)
    {
        $this->jwtGameManager = $jwtGameManager;
    }

    public function createGameToken(array $gameData): string
    {
        return $this->jwtGameManager->createGameToken($gameData);
    }

    public function createGameRefreshToken(array $gameData): string
    {
        return $this->jwtGameManager->createGameRefreshToken($gameData['game_id'], $gameData['user_id']);
    }

    public function createTemporaryToken(string $gameId, int $duration = 300): string
    {
        return $this->jwtGameManager->createTemporaryToken($gameId, $duration);
    }

    public function decodeGameToken(string $token): array
    {
        return $this->jwtGameManager->decodeGameToken($token);
    }

    public function isValidGameToken(string $token): bool
    {
        return $this->jwtGameManager->isValidGameToken($token);
    }

    public function getGameIdFromToken(string $token): ?string
    {
        return $this->jwtGameManager->getGameIdFromToken($token);
    }
}