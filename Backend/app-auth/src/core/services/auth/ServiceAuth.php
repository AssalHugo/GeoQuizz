<?php

namespace app_auth\core\services\auth;

use app_auth\core\dto\AuthDTO;
use app_auth\core\dto\CredentialsDTO;
use app_auth\core\repositoryInterfaces\AuthRepositoryInterface;
use app_auth\core\services\auth\ServiceAuthInterface;
use app_auth\core\services\exceptions\ServiceAuthInvalidDataException;

class ServiceAuth implements ServiceAuthInterface
{
    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function createUser(CredentialsDTO $credentials): string
    {
        $user = $this->authRepository->createUser($credentials->email, $credentials->password);
        return $user->getID();
    }

    public function byCredentials(CredentialsDTO $credentials): AuthDTO
    {
        $user = $this->authRepository->getUserByEmail($credentials->email);
        if (password_verify($credentials->password, $user->password)) {
            return new AuthDTO($user->getID(), $user->email, '', '');
        }
        throw new ServiceAuthInvalidDataException("Invalid credentials");
    }

    public function getUserById(string $id): AuthDTO
    {
        $user = $this->authRepository->getUserById($id);
        if ($user) {
            return new AuthDTO($user->getID(), $user->email, '', '');
        }
        throw new ServiceAuthInvalidDataException("User not found");
    }
}
