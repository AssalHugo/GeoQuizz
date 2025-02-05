<?php

namespace api_geoquizz\core\repositoryInterface\user;

use api_geoquizz\core\domain\entities\geoquizz\User;

interface UserRepositoryInterface {
    public function save(User $user): string;
    public function getUserByEmail(string $email): User;
    public function getUserById(string $id): User;
    public function createUser(string $id, string $email, string $nickname): User;
}
