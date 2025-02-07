<?php

namespace api_geoquizz\application\actions;

use api_geoquizz\application\renderer\JsonRenderer;
use api_geoquizz\core\services\GameServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GetGamesByUserAction extends AbstractAction
{
    private GameServiceInterface $gameService;

    public function __construct(GameServiceInterface $gameService)
    {
        $this->gameService = $gameService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            if (!isset($args['userId'])) {
                throw new \InvalidArgumentException("Missing user ID in URL.");
            }

            $userId = $args['userId'];

            // Récupérer les jeux de l'utilisateur
            $games = $this->gameService->getGamesByUser($userId);

            if (!$games) {
                return JsonRenderer::render($rs, 400, ['message' => 'No games found for this user']);
            }

            return JsonRenderer::render($rs, 200, [
                'games' => $games,
                'links' => [
                    'self' => ['href' => '/users/' . $userId . '/games']
                ]
            ]);
        }
        catch (\Exception $e) {
            return JsonRenderer::render($rs, 400, [
                'message' => $e->getMessage(),
                'error' => get_class($e)
            ]);
        }
    }
}