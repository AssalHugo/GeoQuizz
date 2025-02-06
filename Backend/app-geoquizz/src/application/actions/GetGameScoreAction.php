<?php

namespace api_geoquizz\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use api_geoquizz\core\services\GameServiceInterface;
use Slim\Psr7\Response;
use Slim\App;


class GetGameScoreAction extends AbstractAction {
    private GameServiceInterface $gameService;
    
    public function __construct(GameServiceInterface $gameService) {
        $this->gameService = $gameService;
    }
    
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        $game = $this->gameService->getGameById($args['gameId']);
        
        $rs->getBody()->write(json_encode(['score' => $game->getScore()]));
        return $rs->withHeader('Content-Type', 'application/json');
    }
}
