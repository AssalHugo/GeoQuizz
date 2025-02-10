<?php

namespace api_geoquizz\application\providers;

interface JWTGameProviderInterface
{
    /**
     * Create a JWT token for a game session
     */
    public function createGameToken(array $gameData): string;
    public function createGameRefreshToken(array $gameData): string;
    public function createTemporaryToken(string $gameId, int $duration = 300): string;

    /**
     * Decode and validate a game token
     */
    public function decodeGameToken(string $token): array;

    /**
     * Check if a game token is valid
     */
    public function isValidGameToken(string $token): bool;

    /**
     * Extract game ID from token
     */
    public function getGameIdFromToken(string $token): ?string;
}