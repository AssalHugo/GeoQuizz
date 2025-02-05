<?php

namespace app_auth\core\services\geoquizz;

use app_auth\core\dto\UserDTO;

interface ServiceGeoquizzInterface
{
    public function createUser(UserDTO $user): void;
    public function getUserById(string $id): UserDTO;
    public function getUserByEmail(string $email): UserDTO;
}
