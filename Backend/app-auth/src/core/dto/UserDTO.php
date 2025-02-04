<?php 

namespace app_auth\core\dto;

class UserDTO extends DTO {

    protected string $ID;
    protected string $nickname;
    protected string $email;

    public function __construct($id, $nickname, $email)
    {
        $this->ID = $id;
        $this->nickname = $nickname;
        $this->email = $email;
    }

    public function jsonSerialize(): array{
        return [
            "ID" => $this->ID,
            "nickname" => $this->nickname,
            "email" => $this->email
        ];
    }
}