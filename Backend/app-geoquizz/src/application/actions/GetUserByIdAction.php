<?php

namespace api_geoquizz\application\actions;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use api_geoquizz\core\services\user\UserServiceInterface;
use api_geoquizz\core\services\user\UserServiceEntityNotFoundException;
use api_geoquizz\application\renderer\JsonRenderer;

class GetUserByIdAction extends AbstractAction
{
    protected UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $id = $args['id'];
        try {
            $user = $this->userService->getUserById($id);
        } catch (UserServiceEntityNotFoundException $e) {
            $data = [
                'message' => $e->getMessage(),
                'exception' => [
                    'type' => get_class($e),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ];
            return JsonRenderer::render($rs, 404, $data);
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
            'user' => [
                'id' => $user->ID,
                'nickname' => $user->nickname,
                'email' => $user->email
            ],
            'links' => [
                'self' => ['href' => '/users/' . $user->ID],
            ]
        ];

        return JsonRenderer::render($rs, 200, $data);
    }
}