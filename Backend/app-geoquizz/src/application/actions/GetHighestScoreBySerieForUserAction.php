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
            if (!isset($args['userId'])) {
                throw new \InvalidArgumentException("Missing user ID in URL.");
            }

            $userId = $args['userId'];

            // Récupérer les query params
            $queryParams = $rq->getQueryParams();
            $serieId = $queryParams['serieId'] ?? null;

            $Scores = $this->gameService->getHighestScoreBySerieForUser($serieId, $userId);


            if ($serieId == null) {
                $data = [
                    'score' => $Scores,
                    'links' => [
                        'self' => '/users/' . $userId . '/highest-score'
                    ]
                ];

                return JsonRenderer::render($rs, 200, $data);
            }
            else {
                $data = [
                    'score' => $Scores,
                    'links' => [
                        'self' => '/users/' . $userId . '/series/' . $serieId . '/highest-score'
                    ]
                ];

                return JsonRenderer::render($rs, 200, $data);
            }
        } catch (\Exception $e) {
            $errorData = [
                'error' => 'An error occurred while fetching the highest score.',
                'details' => $e->getMessage()
            ];

            return JsonRenderer::render($rs, 500, $errorData);
        }
    }
}