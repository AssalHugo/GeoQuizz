<?php

namespace app_auth\infrastructure\adaptaters;

use GuzzleHttp\Client;
use app_auth\core\services\geoquizz\ServiceGeoquizzInterface;
use app_auth\core\dto\UserDTO;

class ServiceGeoquizzAdapter implements ServiceGeoquizzInterface
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function createUser(UserDTO $user): UserDTO
    {
        $response = $this->client->post("/users", [
            'user' => $user
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        return new UserDTO($data['id'], $data['nickname'], $data['email']);
    }

    public function getUserById(string $id): UserDTO
    {
        $response = $this->client->get("/users/$id");
        $data = json_decode($response->getBody()->getContents(), true);
        return new UserDTO($data['id'], $data['nickname'], $data['email']);;
    }

    public function getUserByEmail(string $email): UserDTO
    {
        $response = $this->client->get("/users?email=$email");
        $data = json_decode($response->getBody()->getContents(), true);
        return new UserDTO($data['id'], $data['nickname'], $data['email']);
    }
}
