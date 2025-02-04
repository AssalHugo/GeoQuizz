<?php

namespace app_auth\core\domain\entities;

use app_auth\core\domain\entities\Entity;

class User extends Entity
{
    protected string $email;
    protected string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
}