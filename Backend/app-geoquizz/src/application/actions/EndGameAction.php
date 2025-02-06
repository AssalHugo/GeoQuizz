<?php

namespace api_geoquizz\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use api_geoquizz\core\services\GameServiceInterface;
use api_geoquizz\application\renderer\JsonRenderer;
use api_geoquizz\core\repositoryInterface\RepositoryEntityNotFoundException;
use api_geoquizz\core\repositoryInterface\RepositoryEntityConflictException;
use api_geoquizz\core\repositoryInterface\RepositoryEntityValidationException;
use api_geoquizz\core\repositoryInterface\RepositoryConnectionException;
use api_geoquizz\core\repositoryInterface\RepositoryException;

class EndGameAction extends AbstractAction
{
    private GameServiceInterface $gameService;

    public function __construct(GameServiceInterface $gameService)
    {
        $this->gameService = $gameService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        if (!isset($args['gameId'])) {
            $data = [
                'message' => 'Parametre requis manquant: gameId',
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
            $game = $this->gameService->getGameById($args['gameId']);
            $this->gameService->endGame($game);
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
            'message' => 'Game ended successfully',
            'game' => $game
        ];
        return JsonRenderer::render($rs, 200, $data);
    }
}
