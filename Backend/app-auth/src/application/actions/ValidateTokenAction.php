<?php

namespace app_auth\application\actions;

use app_auth\application\providers\auth\AuthProviderInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use app_auth\application\actions\AbstractAction;
use app_auth\application\renderer\JsonRenderer;
use app_auth\core\services\auth\ServiceAuthInvalidDataException;

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
            // Erreur d'authentification (token invalide, expiré...)
            return JsonRenderer::render($rs, 401, [
                'message' => 'Token invalide: ' . $e->getMessage(),
                'error' => [
                    'type' => get_class($e),
                    'code' => 401
                ]
            ]);
        } catch (\Firebase\JWT\ExpiredException $e) {
            // Token expiré
            return JsonRenderer::render($rs, 401, [
                'message' => 'Token expiré',
                'error' => [
                    'type' => get_class($e),
                    'code' => 401,
                ]
            ]);
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            // Signature invalide
            return JsonRenderer::render($rs, 401, [
                'message' => 'Signature du token invalide',
                'error' => [
                    'type' => get_class($e),
                    'code' => 401
                ]
            ]);
        } catch (\Exception $e) {
            // Autres erreurs inattendues
            return JsonRenderer::render($rs, 500, [
                'message' => 'Erreur interne du serveur: ' . $e->getMessage(),
                'error' => [
                    'type' => get_class($e),
                    'code' => 500
                ]
            ]);
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