<?php 

namespace api_geoquizz\core\services\user;

use api_geoquizz\core\dto\UserDTO;

interface UserServiceInterface {
    public function createUser(UserDTO $user): void;
    public function getUserById(string $id): UserDTO;
    public function getUserByEmail(string $email): UserDTO;
}