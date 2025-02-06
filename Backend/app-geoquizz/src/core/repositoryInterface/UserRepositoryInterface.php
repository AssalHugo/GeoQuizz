<?php

namespace api_geoquizz\core\repositoryInterface;

use api_geoquizz\core\domain\entities\geoquizz\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;
    public function findById(string $id): ?User;
}