<?php

namespace api_geoquizz\core\domain\entities\geoquizz;

use api_geoquizz\core\domain\entities\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

class User extends Entity {
    #[ORM\Column(type: Types::STRING, length: 48)]
    private string $id;
    #[ORM\Column(type: Types::STRING, length: 48)]
    private string $nickname;
    #[ORM\Column(type: Types::STRING, length: 48)]
    private string $email;

    //getter
    public function getId(): string
    {
        return $this->id;
    }
    public function getNickName(): string
    {
        return $this->nickname;
    }
    public function getEmail(): string
    {
        return $this->email;
    }



}