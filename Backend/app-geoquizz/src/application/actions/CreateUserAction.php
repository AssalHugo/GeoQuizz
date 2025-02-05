<?php

namespace api_geoquizz\application\actions;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use api_geoquizz\core\dto\UserDTO;
use api_geoquizz\core\services\user\UserServiceEntityNotFoundException;
use api_geoquizz\core\services\user\UserServiceInterface;
use api_geoquizz\application\renderer\JsonRenderer;

class CreateUserAction
{
    private UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $data = $rq->getParsedBody();
        $user = new UserDTO($data['id'], $data['nickname'], $data['email']);
        try {
            $this->userService->createUser($user);
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'exception' => [
                    'type' => get_class($e),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ];
            return JsonRenderer::render($rs, 400, $data);
        }

        $data = [
            'message' => 'User created',
            'links' => [
                'self' => ['href' => '/users'],
            ]
        ];


        return JsonRenderer::render($rs, 201, $data);
    }
}