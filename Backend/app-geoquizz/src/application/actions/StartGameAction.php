<?php

namespace api_geoquizz\application\actions;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use api_geoquizz\core\services\GameService;
use api_geoquizz\application\renderer\JsonRenderer;
use api_geoquizz\core\services\game\GameNotFoundException;
use api_geoquizz\core\services\game\GameServiceException;

class StartGameAction extends AbstractAction {
    private GameService $gameService;
    
    public function __construct(GameService $gameService) {
        $this->gameService = $gameService;
    }
    
    public function __invoke(Request $rq, Response $rs, array $args): Response {
        if (!isset($args['id'])) {
            $data = [
                'message' => 'Missing required parameter: gameId',
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
            $this->gameService->startGame($game);

            $data = [
                'status' => 'Game started',
                'links' => [
                    'self' => ['href' => '/games/' . $args['id']]
                ]
            ];

            return JsonRenderer::render($rs, 200, $data);

        } catch (\Exception $e) {
            $data = [
                'message' => 'Game not found',
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
                'message' => 'Error starting the game',
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
                    'code' => 500,
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ];
            return JsonRenderer::render($rs, 500, $data);
        }
    }
}
