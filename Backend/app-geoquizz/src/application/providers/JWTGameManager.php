<?php

namespace api_geoquizz\application\providers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTGameManager
{
    private string $secret;
    private string $algo;

    public function __construct(string $secret, string $algo)
    {
        $this->secret = $secret;
        $this->algo = $algo;
    }

    public function createGameToken(array $gameData): string
    {
        $payload = [
            'iat' => time(),
            'exp' => time() + 3600, // 1 heure d'expiration
            'game_id' => $gameData['id'],
            'user_id' => $gameData['userId'],
            'serie_id' => $gameData['serieId'],
            'state' => $gameData['state']
        ];

        return JWT::encode($payload, $this->secret, $this->algo);
    }

    public function decodeGameToken(string $token): array
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, $this->algo));
            return (array) $decoded;
        } catch (\Exception $e) {
            throw new \Exception("Invalid game token: " . $e->getMessage() );
        }
    }

    
    public function isValidGameToken(string $token): bool
    {
        try {
            $decoded = $this->decodeGameToken($token);
            error_log('Decoded token: ' . print_r($decoded, true));

            if (isset($decoded['exp']) && $decoded['exp'] < time()) {
                error_log('Token expired');
                return false;
            }

            // Vérifier que c'est bien un token de jeu
            if (!isset($decoded['game_id'])) {
                error_log('No game_id in token');
                return false;
            }

            return true;
        } catch (\Exception $e) {
            error_log('Exception during token validation: ' . $e->getMessage());
            return false;
        }
    }

    public function createGameRefreshToken(string $gameId, string $userId): string
    {
        $payload = [
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24 * 30), // 30 days expiration
            'game_id' => $gameId,
            'user_id' => $userId,
        ];

        return JWT::encode($payload, $this->secret, $this->algo);
    }
    
    public function createTemporaryToken(string $gameId, int $duration): string
    {
        $payload = [
            'iat' => time(),
            'exp' => time() + $duration,
            'game_id' => $gameId,
        ];

        return JWT::encode($payload, $this->secret, $this->algo);
    }

    public function getGameIdFromToken(string $token): ?string
    {
        try {
            $decoded = $this->decodeGameToken($token);
            return $decoded['game_id'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }
}