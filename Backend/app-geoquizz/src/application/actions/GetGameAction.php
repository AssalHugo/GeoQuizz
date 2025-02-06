<?php

namespace api_geoquizz\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use api_geoquizz\core\services\GameServiceInterface;
use api_geoquizz\application\renderer\JsonRenderer;
use Slim\Psr7\Response;
use Slim\App;

class GetGameAction extends AbstractAction {
    private GameServiceInterface $gameService;
    
    public function __construct(GameServiceInterface $gameService) {
        $this->gameService = $gameService;
    }
    
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        try {
            if (!isset($args['id'])) {
                throw new \InvalidArgumentException("Missing game ID in URL.");
            }
    
            $gameId = $args['id']; // Récupère l'ID de l'URL
            $game = $this->gameService->getGameById($gameId);
    
            if (!$game) {
                return JsonRenderer::render($rs, 404, ['message' => 'Game not found']);
            }
    
            return JsonRenderer::render($rs, 200, [
                'gameId' => $game->getId(),
                'score' => $game->getScore(),
                'state' => $game->getState(),
                'serieId' => $game->getSerieId(),
                'photos' => $game->getScore(),
                'current-photo-index' => $game->getCurrentPhotoIndex(),
                'start-time' => $game->getStartTime(),
                
            ]);
        } catch (\Exception $e) {
            return JsonRenderer::render($rs, 400, [
                'message' => $e->getMessage(),
                'error' => get_class($e)
            ]);
        }
    }
    

}