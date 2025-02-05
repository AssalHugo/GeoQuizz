<?php

namespace api_geoquizz\application\actions;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use api_geoquizz\core\services\user\UserServiceInterface;
use api_geoquizz\core\services\user\UserServiceEntityNotFoundException;
use api_geoquizz\application\renderer\JsonRenderer;

class GetUserByEmailAction extends AbstractAction
{
    protected UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $email = $rq->getQueryParams()['email'] ?? null;

        if (is_null($email)) {
            $data = [
                'message' => 'Missing email parameter',
                'exception' => [
                    'type' => 'InvalidArgumentException',
                    'code' => 400,
                    'file' => __FILE__,
                    'line' => __LINE__
                ]
            ];
            return JsonRenderer::render($rs, 400, $data);
        }
        try {
            $userDTO = $this->userService->getUserByEmail($email);
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
                'id' => $userDTO->id,
                'nickname' => $userDTO->nickname,
                'email' => $userDTO->email
            ],
            'links' => [
                'self' => ['href' => '/users?email=' . $email],
            ]
        ];

        return JsonRenderer::render($rs, 200, $data);
    }
}
