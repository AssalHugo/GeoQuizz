<?php

namespace api_geoquizz\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use api_geoquizz\core\services\GameServiceInterface;
use Slim\Psr7\Response;
use Slim\App;

class CreateGameAction extends AbstractAction {
    private GameServiceInterface $gameService;
    
    public function __construct(GameServiceInterface $gameService) {
        $this->gameService = $gameService;
    }
    
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        $body = $rq->getParsedBody();
        $game = $this->gameService->createGame($body['serieId'], $body['username']);
        $rs->getBody()->write(json_encode(['gameId' => $game->getId()]));
        return $rs->withHeader('Content-Type', 'application/json');
    }
}
