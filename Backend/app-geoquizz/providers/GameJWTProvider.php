<?php

namespace api_geoquizz\application\providers\game;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class GameJWTProvider
{
    private string $secretKey;
    private string $algorithm;
    private int $expiration;

    public function __construct(string $secretKey, string $algorithm = 'HS256', int $expiration = 3600)
    {
        $this->secretKey = $secretKey;
        $this->algorithm = $algorithm;
        $this->expiration = $expiration;
    }

    public function generateToken(array $gameData): string
    {
        $payload = [
            'gameId' => $gameData['id'],
            'userId' => $gameData['userId'],
            'serieId' => $gameData['serieId'],
            'iat' => time(),
            'exp' => time() + $this->expiration
        ];

        return JWT::encode($payload, $this->secretKey, $this->algorithm);
    }

    public function validateToken(string $token): array
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, $this->algorithm));
            return (array)$decoded;
        } catch (\Exception $e) {
            throw new \RuntimeException('Token invalide : ' . $e->getMessage());
        }
    }
}