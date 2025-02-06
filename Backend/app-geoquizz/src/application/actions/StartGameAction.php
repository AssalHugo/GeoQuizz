<?php

namespace api_geoquizz\application\actions;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use api_geoquizz\core\services\GameServiceInterface;
use api_geoquizz\application\renderer\JsonRenderer;
use api_geoquizz\core\repositoryInterface\RepositoryEntityNotFoundException;
use api_geoquizz\core\repositoryInterface\RepositoryEntityConflictException;
use api_geoquizz\core\repositoryInterface\RepositoryEntityValidationException;
use api_geoquizz\core\repositoryInterface\RepositoryConnectionException;
use api_geoquizz\core\repositoryInterface\RepositoryException;

class StartGameAction extends AbstractAction
{
    private GameServiceInterface $gameService;

    public function __construct(GameServiceInterface $gameService)
    {
        $this->gameService = $gameService;
    }

    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
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
        } catch (RepositoryEntityNotFoundException $e) {
            $data = [
                'message' => $e->getMessage(),
                'error' => [
                    'type' => get_class($e),
                    'code' => 404,
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ];
            return JsonRenderer::render($rs, 404, $data);
        } catch (RepositoryEntityConflictException $e) {
            $data = [
                'message' => $e->getMessage(),
                'error' => [
                    'type' => get_class($e),
                    'code' => 409,
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ];
            return JsonRenderer::render($rs, 409, $data);
        } catch (RepositoryEntityValidationException $e) {
            $data = [
                'message' => $e->getMessage(),
                'error' => [
                    'type' => get_class($e),
                    'code' => 400,
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ];
            return JsonRenderer::render($rs, 400, $data);
        } catch (RepositoryConnectionException $e) {
            $data = [
                'message' => $e->getMessage(),
                'error' => [
                    'type' => get_class($e),
                    'code' => 503,
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ];
            return JsonRenderer::render($rs, 503, $data);
        } catch (RepositoryException $e) {
            $data = [
                'message' => $e->getMessage(),
                'error' => [
                    'type' => get_class($e),
                    'code' => 500,
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ];
            return JsonRenderer::render($rs, 500, $data);
        } catch (\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'error' => [
                    'type' => get_class($e),
                    'code' => 500,
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ];
            return JsonRenderer::render($rs, 500, $data);
        }

        $data = [
            'status' => 'Game started',
            'links' => [
                'self' => ['href' => '/games/' . $args['id']]
            ]
        ];

        return JsonRenderer::render($rs, 200, $data);
    }
}
