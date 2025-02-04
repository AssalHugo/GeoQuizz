<?php

namespace app_auth\core\services\auth;

use app_auth\core\dto\AuthDTO;
use app_auth\core\dto\CredentialsDTO;

interface ServiceAuthInterface {
    public function createUser(CredentialsDTO $credentials): string;
    public function byCredentials(CredentialsDTO $credentials): AuthDTO;
    public function getUserById(string $id): AuthDTO;
}