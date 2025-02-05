<?php 

namespace app_auth\core\dto;

class AuthDTO extends DTO {
    
    protected string $ID;
    protected string $email;
    protected string $accessToken;
    protected string $refreshToken;

    public function __construct($ID, $email, $accessToken, $refreshToken) {
        $this->ID = $ID;
        $this->email = $email;
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
    }

    public function jsonSerialize(): array{
        return [
            "ID" => $this->ID,
            "email" => $this->email,
            "accessToken" => $this->accessToken,
            "refreshToken" => $this->refreshToken
        ];
    }
}