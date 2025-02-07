<?php

namespace app_auth\application\actions;

use app_auth\application\providers\auth\AuthProviderInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use app_auth\core\dto\CredentialsDTO;
use app_auth\core\services\auth\ServiceAuthInvalidDataException;
use app_auth\application\renderer\JsonRenderer;

class SignInAction extends AbstractAction
{
    protected AuthProviderInterface $authProvider;

    public function __construct(AuthProviderInterface $authProvider)
    {
        $this->authProvider = $authProvider;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $data = $rq->getParsedBody();
        $credentials = new CredentialsDTO($data['email'], $data['password']);

        try {
            $authDTO = $this->authProvider->signIn($credentials);
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
            return JsonRenderer::render($rs, 400, $data);
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
            'token' => $authDTO->accessToken,
            'links' => [
                'self' => ['href' => '/auth/signin'],
                'refresh' => ['href' => '/auth/refresh']
            ]
        ];

        return JsonRenderer::render($rs, 200, $data);
    }
}