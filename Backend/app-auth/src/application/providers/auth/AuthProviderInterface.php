<?php 

namespace app_auth\application\providers\auth;

use app_auth\core\dto\AuthDTO;
use app_auth\core\dto\CredentialsDTO;

interface AuthProviderInterface {
    public function register(CredentialsDTO $credentials): string;
    public function signin(CredentialsDTO $credentials): AuthDTO;
    public function refresh(string $refreshToken): AuthDTO;
    public function getSignedInUser(string $token): AuthDTO;
}