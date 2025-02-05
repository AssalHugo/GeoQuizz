<?php 

namespace app_auth\core\repositoryInterfaces;

use app_auth\core\domain\entities\User;

interface AuthRepositoryInterface
{
    public function save(User $user): string;
    public function getUserByEmail(string $email): User;
    public function getUserById(string $id): User;
    public function createUser(string $email, string $password): User;

}