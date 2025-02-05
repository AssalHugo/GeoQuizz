<?php

namespace api_geoquizz\core\services\user;

use api_geoquizz\core\dto\UserDTO;
use api_geoquizz\core\services\user\UserServiceInterface;
use api_geoquizz\core\repositoryInterface\user\UserRepositoryInterface;

class UserService implements UserServiceInterface {

    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function createUser(UserDTO $user): void {
        $this->userRepository->createUser($user->id, $user->email, $user->nickname);
    }

    public function getUserById(string $id): UserDTO {
        $user = $this->userRepository->getUserById($id);
        return new UserDTO($user->getId(), $user->getNickName(), $user->getEmail());
    }
    public function getUserByEmail(string $email): UserDTO {
        $user = $this->userRepository->getUserByEmail($email);
        return new UserDTO($user->getId(), $user->getNickName(), $user->getEmail());
    }
}