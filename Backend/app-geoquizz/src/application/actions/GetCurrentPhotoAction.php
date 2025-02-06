<?php

namespace api_geoquizz\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use api_geoquizz\core\services\GameServiceInterface;
use api_geoquizz\application\renderer\JsonRenderer;
use api_geoquizz\core\services\game\GameServiceException;

class GetCurrentPhotoAction extends AbstractAction 
{
    private GameServiceInterface $gameService;
    
    public function __construct(GameServiceInterface $gameService) {
        $this->gameService = $gameService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        // Vérification si gameId est présent dans l'URL
        if (!isset($args['id'])) {
            $data = [
                'message' => 'Parametre requis manquant: gameId',
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
            // Récupérer le jeu par son ID passé dans l'URL
            $game = $this->gameService->getGameById($args['id']);
            
            // Récupérer la photo actuelle du jeu
            $photo = $this->gameService->getCurrentPhoto($game);
    
            // Si aucune photo n'est trouvée, on renvoie une erreur
            if (!$photo) {
                $data = [
                    'message' => 'Aucune photo actuelle trouvée pour ce jeu',
                    'exception' => [
                        'type' => 'NotFoundException',
                        'code' => 404,
                        'file' => __FILE__,
                        'line' => __LINE__
                    ]
                ];
                return JsonRenderer::render($rs, 404, $data);
            }
        
    
            // Retourner la photo dans la réponse
            $data = [
                'id' => $photo->getId(),
                'photo' => $photo->getPhoto(),
                'latitude' => $photo->getLatitude(),
                'longitude' => $photo->getLongitude(),
                'adresse' => $photo->getAdresse(),
                'serie' => $photo->getSerie(),  // Si vous souhaitez renvoyer des informations sur la série, vous pouvez appeler un getter spécifique pour `Serie`
                'links' => [
                    'self' => ['href' => '/games/' . $args['id'] . '/current-photo']
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
    
        } catch (\Exception $e) {
            // Erreur générique si quelque chose échoue
            $data = [
                'message' => $e->getMessage(),
                'exception' => [
                    'type' => get_class($e),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ];
            return JsonRenderer::render($rs, 500, $data);
        }
    }
    
}
