<?php

namespace api_geoquizz\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use api_geoquizz\core\services\GameServiceInterface;
use api_geoquizz\application\renderer\JsonRenderer;

class GetHighestScoreBySerieForUserAction extends AbstractAction
{
    private GameServiceInterface $gameService;

    public function __construct(GameServiceInterface $gameService)
    {
        $this->gameService = $gameService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $userId = $args['userId'];
            $serieId = $args['serieId'];

            $highestScore = $this->gameService->getHighestScoreBySerieForUser($serieId, $userId);

            $data = [
                'highestScore' => $highestScore,
                'links' => [
                    'self' => '/series/' . $serieId . '/users/' . $userId . '/highest-score',
                    'user' => '/users/' . $userId,
                    'serie' => '/series/' . $serieId
                ]
            ];

            return JsonRenderer::render($rs, 200, $data);
        } catch (\Exception $e) {
            $errorData = [
                'error' => 'An error occurred while fetching the highest score.',
                'details' => $e->getMessage()
            ];

            return JsonRenderer::render($rs, 500, $errorData);
        }
    }
}