<?php

namespace app_auth\application\actions;

use app_auth\application\providers\auth\AuthProviderInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use app_auth\core\dto\CredentialsDTO;
use app_auth\core\dto\UserDTO;
use app_auth\core\services\exceptions\ServiceAuthInvalidDataException;
use app_auth\application\renderer\JsonRenderer;
use app_auth\core\services\geoquizz\ServiceGeoquizzInterface;

class RegisterAction extends AbstractAction
{   
    protected AuthProviderInterface $authProvider;
    protected ServiceGeoquizzInterface $serviceGeoquizz;

    public function __construct(AuthProviderInterface $authProvider, ServiceGeoquizzInterface $serviceGeoquizz)
    {
        $this->authProvider = $authProvider;
        $this->serviceGeoquizz = $serviceGeoquizz;
    }
  
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $data = $rq->getParsedBody();
        $credentials = new CredentialsDTO($data['email'], $data['password']);

        try {
            $id = $this->authProvider->register($credentials);
            $userDTO = new UserDTO($id, $data['nickname'],$data['email']);
            $this->serviceGeoquizz->createUser($userDTO);
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
            'message' => 'User registered successfully',
            'links' => [
                'self' => ['href' => '/auth/register'],
                'signin' => ['href' => '/auth/signin'],
            ]
        ];

        return JsonRenderer::render($rs, 201, $data);
    }
}