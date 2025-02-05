<?php 

namespace app_auth\core\dto;

class UserDTO extends DTO {

    protected string $id;
    protected ?string $nickname = null;
    protected string $email;

    public function __construct(string $id, ?string $nickname, string $email)
    {
        $this->id = $id;
        $this->nickname = $nickname;
        $this->email = $email;
    }

    public function jsonSerialize(): array{
        return [
            "id" => $this->id,
            "nickname" => $this->nickname,
            "email" => $this->email
        ];
    }
}