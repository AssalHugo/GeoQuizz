<?php

namespace api_geoquizz\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use api_geoquizz\core\services\GameServiceInterface;
use api_geoquizz\application\renderer\JsonRenderer;

class GetNextPhotoAction extends AbstractAction 
{
    private GameServiceInterface $gameService;
    
    public function __construct(GameServiceInterface $gameService) {
        $this->gameService = $gameService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        // Vérification de l'existence du gameId dans l'URL
        if (!isset($args['id'])) {
            $data = [
                'message' => 'Paramètre requis manquant : gameId',
                'exception' => [
                    'type' => 'InvalidArgumentException', 
                    'code' => 400,
                    'file' => __FILE__,
                    'line' => __LINE__
                ]
            ];
            return JsonRenderer::render($rs, 400, $data);
        }

        try {
            // Récupérer le jeu via son ID
            $game = $this->gameService->getGameById($args['id']);
            
            // Récupérer la prochaine photo du jeu
            $photo = $this->gameService->getNextPhoto($game);

            // Si aucune photo suivante n'est trouvée
            if (!$photo) {
                $data = [
                    'message' => 'Aucune photo suivante trouvée pour ce jeu.',
                    'exception' => [
                        'type' => 'NotFoundException',
                        'code' => 404,
                        'file' => __FILE__,
                        'line' => __LINE__
                    ]
                ];
                return JsonRenderer::render($rs, 404, $data);
            }

            // Retourner la prochaine photo
            $data = [
                'id' => $photo->getId(),
                'photo' => $photo->getPhoto(),
                'latitude' => $photo->getLatitude(),
                'longitude' => $photo->getLongitude(),
                'adresse' => $photo->getAdresse(),
                'serie' => $photo->getSerie(),
                'links' => [
                    'self' => ['href' => '/games/' . $args['id'] . '/next-photo']
                ]
            ];

            return JsonRenderer::render($rs, 200, $data);

        } catch (\Exception $e) {
            // Erreur si le jeu n'est pas trouvé
            $data = [
                'message' => $e->getMessage(),
                'exception' => [
                    'type' => get_class($e),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ];
            return JsonRenderer::render($rs, 404, $data);
        }
    }
}
