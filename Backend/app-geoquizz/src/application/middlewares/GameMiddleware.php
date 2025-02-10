<?php

namespace api_geoquizz\application\middlewares;

use api_geoquizz\application\providers\JWTGameManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class GameMiddleware
{
    private JWTGameManager $jwtGameManager;

    public function __construct(JWTGameManager $jwtGameManager)
    {
        $this->jwtGameManager = $jwtGameManager;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        //CORS
        $response = $handler->handle($request);
        $response = $response->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');

        $authHeader = $request->getHeaderLine('Authorization');

        if ($authHeader) {
            $token = str_replace('Bearer ', '', $authHeader);
        try{
            if ($this->jwtGameManager->isValidGameToken($token)) 
            {
                $gameId = $this->jwtGameManager->getGameIdFromToken($token);
                $request = $request->withAttribute('gameId', $gameId);
                return $handler->handle($request);
            }
        } catch(\Exception $e){
            $data = [
                'message' => $e->getMessage(),
                'error' => [
                    'type' => get_class($e),
                    'code' => 500,
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ];
        }
            
        }

        return $handler->handle($request);
    }
}