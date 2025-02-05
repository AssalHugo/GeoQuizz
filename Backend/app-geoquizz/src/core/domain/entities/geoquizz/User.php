<?php

namespace api_geoquizz\core\domain\entities\geoquizz;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Table(name: "user")]
class User {

    #[ORM\Id]
    #[ORM\Column(name: "id", type: Types::GUID)]
    private string $id;
    #[ORM\Column(name: "nickname", type: Types::STRING, length: 48)]
    private string $nickname;
    #[ORM\Column(name: "email", type: Types::STRING, length: 48)]
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

    // Setters
    public function setNickName(string $nickname): self
    {
        $this->nickname = $nickname;
        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function setID(string $id):self
    {
        $this->id = $id;
        return $this;
    }

}