<?php

namespace app_auth\application\providers\auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTManager
{

    private string $secret;
    private string $algo;

    public function __construct(string $secret, string $algo)
    {
        $this->secret = $secret;
        $this->algo = $algo;
    }

    public function createAccessToken(array $payload): string
    {
        return JWT::encode($payload, $this->secret, $this->algo);
    }

    public function createRefreshToken(array $payload): string
    {
        $refreshPayload = [
            'iat' => time(),
            'exp' => time() + (3600 * 24 * 7),
            'sub' => $payload['sub'],
            'data' => $payload['data'],
        ];

        return $this->createAccessToken($refreshPayload);
    }

    public function decodeToken(string $token): array
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, $this->algo));

            return (array) $decoded;
        } catch (\Exception $e) {
            throw new \Exception("Invalid token: " . $e->getMessage());
        }
    }

    public function isValidToken(string $token): bool
    {
        $decoded = $this->decodeToken($token);

        if (isset($decoded['exp']) && $decoded['exp'] < time()) {
            return false;
        }
        return true;
    }
}
