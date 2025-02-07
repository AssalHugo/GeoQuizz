<?php

namespace api_geoquizz\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use api_geoquizz\core\services\GameServiceInterface;
use api_geoquizz\application\renderer\JsonRenderer;
use Slim\Psr7\Response;
use Slim\App;

class GetGameByIdAction extends AbstractAction {
    private GameServiceInterface $gameService;
    
    public function __construct(GameServiceInterface $gameService) {
        $this->gameService = $gameService;
    }
    
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface 
{
    try {
        if (!isset($args['id'])) {
            throw new \InvalidArgumentException("Missing game ID in URL.");
        }
        
        $gameId = $args['id'];
        $game = $this->gameService->getGameById($gameId);
        
        if (!$game) {
            return JsonRenderer::render($rs,400, ['message' => 'Game not found']);
        }

        // Utiliser jsonSerialize() du DTO directement
        return JsonRenderer::render($rs,200, [
            'game' => $game
        ]);
    } catch (\Exception $e) {
        return JsonRenderer::render($rs, 400,[
            'message' => $e->getMessage(),
            'error' => get_class($e)
        ]);
    }
}
    

}