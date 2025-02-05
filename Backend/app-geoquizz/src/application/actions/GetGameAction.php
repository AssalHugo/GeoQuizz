<?php

namespace api_geoquizz\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use api_geoquizz\core\services\GameService;
use Slim\Psr7\Response;
use Slim\App;

class GetGameAction extends AbstractAction {
    private GameService $gameService;
    
    public function __construct(GameService $gameService) {
        $this->gameService = $gameService;
    }
    
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        $game = $this->gameService->getGameById($args['gameId']);
        if (!$game) {
            return $rs->withStatus(404)->withHeader('Content-Type', 'application/json')
                      ->getBody()->write(json_encode(['error' => 'Game not found']));
        }
        
        $rs->getBody()->write(json_encode([
            'gameId' => $game->getId(),
            'userId' => $game->getUserId(),
            'serieId' => $game->getSerieId(),
            'score' => $game->getScore(),
            'state' => $game->getState()
        ]));
        return $rs->withHeader('Content-Type', 'application/json');
    }

}