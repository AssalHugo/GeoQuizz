<?php

namespace api_geoquizz\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use api_geoquizz\core\services\GameService;
use Slim\Psr7\Response;
use Slim\App;

class PlayGameAction extends AbstractAction {
    private GameService $gameService;
    
    public function __construct(GameService $gameService) {
        $this->gameService = $gameService;
    }
    
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        $body = $rq->getParsedBody();
        $game = $this->gameService->getGameById($args['gameId']);
        $this->gameService->updateGameProgress($game, $body['latitude'], $body['longitude']);
        $rs->getBody()->write(json_encode(['score' => $game->getScore()]));
        return $rs->withHeader('Content-Type', 'application/json');
    }
}