<?php

namespace api_geoquizz\application\middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use api_geoquizz\application\providers\game\GameJWTProvider;
use api_geoquizz\application\renderer\JsonRenderer;
use Slim\Psr7\Response;

class GameTokenMiddleware implements MiddlewareInterface
{
    private GameJWTProvider $jwtProvider;

    public function __construct(GameJWTProvider $jwtProvider)
    {
        $this->jwtProvider = $jwtProvider;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $authHeader = $request->getHeaderLine('Authorization');
        
        // Vérification si le token est présent
        if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            // Créez une nouvelle instance de Response
            $response = new Response();
            return JsonRenderer::render($response, 401, null, 'Token manquant');
        }

        try {
            // Validation du token JWT
            $payload = $this->jwtProvider->validateToken($matches[1]);
            
            // Ajouter le payload du token au request
            $request = $request->withAttribute('gamePayload', $payload);
        } catch (\RuntimeException $e) {
            // Créez une nouvelle instance de Response pour l'erreur
            $response = new Response();
            // Si le token est invalide ou expiré, renvoyer une erreur
            return JsonRenderer::render($response, 401, null, $e->getMessage());
        }

        // Continuer le traitement de la requête
        return $handler->handle($request);
    }
}
