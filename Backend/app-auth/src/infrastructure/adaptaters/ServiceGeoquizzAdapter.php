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

    public function createUser(UserDTO $user): void
    {
        $response = $this->client->post("/users", [
            'json' => [
                'id' => $user->id,
                'email' => $user->email,
                'nickname' => $user->nickname
            ]
        ]);
        
        if($response->getStatusCode() !== 201){
            throw new \Exception("Error while creating user", $response->getStatusCode());
        }
    }

    public function getUserById(string $id): UserDTO
    {
        $response = $this->client->get("/users/$id");
        $data = json_decode($response->getBody()->getContents(), true);
        return new UserDTO($data['user']['id'], $data['user']['nickname'], $data['user']['email']);
    }

    public function getUserByEmail(string $email): UserDTO
    {
        $response = $this->client->get("/users", [
            'query' => ['email' => $email]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        return new UserDTO($data['user']['id'], $data['user']['nickname'], $data['user']['email']);
    }
}
