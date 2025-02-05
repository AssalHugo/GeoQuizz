<?php

namespace api_geoquizz\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use api_geoquizz\core\services\GameService;
use Slim\Psr7\Response;

class GameAction extends AbstractAction {
    private GameService $gameService;

    public function __construct(GameService $gameService) {
        $this->gameService = $gameService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        $rs->getBody()->write(json_encode(["error" => "Not Found"]));
        return $rs->withStatus(404)->withHeader('Content-Type', 'application/json');
    }
    

    public function createGame(ServerRequestInterface $rq, ResponseInterface $rs): ResponseInterface {
        $data = $rq->getParsedBody();
        $game = $this->gameService->createGame($data['serieId'], $data['userId']);
        $rs->getBody()->write(json_encode(["gameId" => $game->getId()]));
        return $rs->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    public function getGame(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        $game = $this->gameService->getGameById($args['id']);
        if (!$game) {
            return $rs->withStatus(404)->withHeader('Content-Type', 'application/json')
                      ->getBody()->write(json_encode(["error" => "Game not found"]));
        }
        $rs->getBody()->write(json_encode($game));
        return $rs->withHeader('Content-Type', 'application/json');
    }

    public function startGame(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        $game = $this->gameService->getGameById($args['id']);
        if (!$game) {
            return $rs->withStatus(404)->withHeader('Content-Type', 'application/json')
                      ->getBody()->write(json_encode(["error" => "Game not found"]));
        }
        $this->gameService->startGame($game);
        return $rs->withStatus(200)->withHeader('Content-Type', 'application/json');
    }

    public function endGame(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        $game = $this->gameService->getGameById($args['id']);
        if (!$game) {
            return $rs->withStatus(404)->withHeader('Content-Type', 'application/json')
                      ->getBody()->write(json_encode(["error" => "Game not found"]));
        }
        $this->gameService->endGame($game);
        return $rs->withStatus(200)->withHeader('Content-Type', 'application/json');
    }
}
