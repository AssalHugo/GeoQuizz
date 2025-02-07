<?php

namespace app_auth\application\providers\auth;

use app_auth\core\dto\AuthDTO;
use app_auth\core\dto\CredentialsDTO;
use app_auth\application\providers\auth\JWTManager;
use app_auth\core\services\auth\ServiceAuthInterface;
use app_auth\core\services\auth\ServiceAuthInvalidDataException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

class JWTAuthProvider implements AuthProviderInterface {
    private ServiceAuthInterface $authService;
    private JWTManager $jwtManager;

    public function __construct(ServiceAuthInterface $authService, JWTManager $jwtManager) {
        $this->authService = $authService;
        $this->jwtManager = $jwtManager;
    }

    public function register(CredentialsDTO $credentials): string {
        $id = $this->authService->createUser($credentials);
        return $id;
    }

    public function signin(CredentialsDTO $credentials): AuthDTO {
        $user = $this->authService->byCredentials($credentials);
        
        if (!$user) {
            throw new \Exception('Invalid credentials');
        }

        $payload = [
            'iat' => time(),
            'exp' => time() + 3600,
            'sub' => $user->ID,
            'data' => [
                'user' => $user->email,
            ]
        ];

        $accessToken = $this->jwtManager->createAccessToken($payload);
        $refreshToken = $this->jwtManager->createRefreshToken($payload);

        return new AuthDTO($user->ID, $user->email, $accessToken, $refreshToken);
    }

    public function refresh(string $refreshToken): AuthDTO {
        $decodedToken = $this->jwtManager->decodeToken($refreshToken);
        $userId = $decodedToken['sub'];

        $user = $this->authService->getUserById($userId); 

        if (!$user) {
            throw new \Exception('Invalid refresh token');
        }

        $payload = [
            'iat' => time(),
            'exp' => time() + 3600,
            'sub' => $user->ID,
            'data' => [
                'user' => $user->email,
            ]
        ];

        $newAccessToken = $this->jwtManager->createAccessToken($payload);
        $newRefreshToken = $this->jwtManager->createRefreshToken($payload);

        return new AuthDTO($user->ID, $user->email, $newAccessToken, $newRefreshToken);
    }

    public function getSignedInUser(string $token): AuthDTO
    {
        try {
            $decodedToken = $this->jwtManager->decodeToken($token);

            if (!$decodedToken) {
                throw new SignatureInvalidException('Token invalide');
            }

            // Vérifier l'expiration
            if (isset($decodedToken['exp']) && $decodedToken['exp'] < time()) {
                throw new ExpiredException('Token expiré');
            }

            $user = $this->authService->getUserById($decodedToken['sub']);

            if (!$user) {
                throw new ServiceAuthInvalidDataException('Utilisateur non trouvé');
            }

            $refreshToken = $this->jwtManager->createRefreshToken($decodedToken);

            return new AuthDTO($user->ID, $user->email, $token, $refreshToken);
        } catch (SignatureInvalidException $e) {
            throw $e;
        } catch (ExpiredException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new ServiceAuthInvalidDataException($e->getMessage());
        }
    }
}
