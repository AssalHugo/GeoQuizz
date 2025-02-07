<?php

namespace api_geoquizz\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use api_geoquizz\core\services\GameServiceInterface;
use api_geoquizz\application\renderer\JsonRenderer;
use Slim\Psr7\Response;
use api_geoquizz\core\repositoryInterface\RepositoryEntityNotFoundException;
use api_geoquizz\core\repositoryInterface\RepositoryConnectionException;
use api_geoquizz\core\repositoryInterface\RepositoryException;

class GetGamesAction extends AbstractAction {
    private GameServiceInterface $gameService;

    public function __construct(GameServiceInterface $gameService) {
        $this->gameService = $gameService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        try {
            $games = $this->gameService->getGames();

            if (empty($games)) {
                return JsonRenderer::render($rs, 404, [
                    'message' => 'Aucun jeu trouvé.',
                    'games' => []
                ]);
            }

            return JsonRenderer::render($rs, 200, [
                'games' => $games
            ]);

        } catch (RepositoryEntityNotFoundException $e) {
            return JsonRenderer::render($rs, 404, [
                'message' => 'Aucun jeu trouvé.',
                'error' => get_class($e)
            ]);

        } catch (RepositoryConnectionException $e) {
            return JsonRenderer::render($rs, 503, [
                'message' => 'Service indisponible : problème de connexion à la base de données.',
                'error' => get_class($e)
            ]);

        } catch (RepositoryException $e) {
            return JsonRenderer::render($rs, 500, [
                'message' => 'Erreur interne du serveur lors de la récupération des jeux.',
                'error' => get_class($e)
            ]);

        } catch (\Exception $e) {
            return JsonRenderer::render($rs, 400, [
                'message' => 'Erreur inattendue.',
                'error' => get_class($e),
                'details' => $e->getMessage()
            ]);
        }
    }
}
