<?php

namespace app_auth\application\actions;

use app_auth\application\providers\auth\AuthProviderInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use app_auth\application\actions\AbstractAction;
use app_auth\application\renderer\JsonRenderer;
use app_auth\core\services\exceptions\ServiceAuthInvalidDataException;

class ValidateTokenAction extends AbstractAction
{

    protected AuthProviderInterface $authProvider;

    public function __construct(AuthProviderInterface $authProvider)
    {
        $this->authProvider = $authProvider;
    }

    public function __invoke(RequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $authorizationHeader = $rq->getHeader('Authorization');
        $token = isset($authorizationHeader[0]) ? substr($authorizationHeader[0], 7) : null;

        if (!$token) {
            $data = [
                'message' => 'Token not found in request header',
                'exception' => [
                    'type' => 'TokenNotFoundException',
                    'code' => 401,
                    'file' => __FILE__,
                    'line' => __LINE__
                ]
            ];
            return JsonRenderer::render($rs, 401, $data);
        }

        try {
            $authDTO = $this->authProvider->getSignedInUser($token);
        } catch (ServiceAuthInvalidDataException $e) {
            $data = [
                'message' => $e->getMessage(),
                'exception' => [
                    'type' => get_class($e),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ];
            return JsonRenderer::render($rs, 401, $data);
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
            return JsonRenderer::render($rs, 500, $data);
        }

        $data = [
            'message' => 'Token is valid',
            'token' => $token,
            'payload' => [
                'sub' => $authDTO->ID,
                'data' => [
                    'user' => $authDTO->email,                    
                ]
            ],
            'links' => [
                'self' => ['href' => '/tokens/validate'],
            ]
        ];

        return JsonRenderer::render($rs, 200, $data);
    }
}