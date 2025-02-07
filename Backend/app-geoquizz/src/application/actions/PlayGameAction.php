<?php

namespace api_geoquizz\application\actions;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use api_geoquizz\application\renderer\JsonRenderer;
use api_geoquizz\core\services\game\GameServiceException;
use api_geoquizz\core\services\game\GameNotFoundException;
use api_geoquizz\core\services\GameServiceInterface;

class PlayGameAction extends AbstractAction {
    private GameServiceInterface $gameService;
    
    public function __construct(GameServiceInterface $gameService) {
        $this->gameService = $gameService;
    }
    
    public function __invoke(Request $rq, Response $rs, array $args): Response {
        $body = $rq->getParsedBody();

        if (!isset($args['id'], $body['latitude'], $body['longitude'])) {
            $data = [
                'message' => 'Parametre requis manquant: gameId, latitude, et longitude',
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
            $game = $this->gameService->getGameById($args['id']);
            $this->gameService->giveAnswer($game, $body['latitude'], $body['longitude']);

            $data = [
                'score' => $game->score,
                'links' => [
                    'self' => ['href' => '/games/' . $args['id']]
                ]
            ];

            return JsonRenderer::render($rs, 200, $data);

        } catch (\Exception $e) {
            $data = [
                'message' => 'Game not found'. $e->getMessage(),
                'exception' => [
                    'type' => get_class($e),
                    'code' => 404,
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
            return JsonRenderer::render($rs, 500, $data);

        } catch (\Exception $e) {
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
