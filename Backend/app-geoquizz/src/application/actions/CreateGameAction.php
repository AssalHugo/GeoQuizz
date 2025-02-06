<?php

namespace api_geoquizz\application\actions;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use api_geoquizz\core\services\GameServiceInterface;
use api_geoquizz\application\renderer\JsonRenderer;
use api_geoquizz\core\services\game\GameServiceException;

class CreateGameAction extends AbstractAction {
    private GameServiceInterface $gameService;
    
    public function __construct(GameServiceInterface $gameService) {
        $this->gameService = $gameService;
    }
    
    public function __invoke(Request $rq, Response $rs, array $args): Response {
        $body = $rq->getParsedBody();

        if (!isset($body['serieId']) || !isset($body['username'])) {
            $data = [
                'message' => 'Parametre requis manquant: serieId and username',
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
            $game = $this->gameService->createGame($body['serieId'], $body['username']);

            $data = [
                'gameId' => $game->getId(),
                'links' => [
                    'self' => ['href' => '/games/' . $game->getId()]
                ]
            ];

            return JsonRenderer::render($rs, 201, $data);

        }  catch (\Exception $e) {
            $data = [
                'message' => 'An unexpected error occurred.',
                'exception' => [
                    'type' => get_class($e),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ];
            return JsonRenderer::render($rs, 400, $data);
        }
    }
}
