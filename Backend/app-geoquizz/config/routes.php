<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use api_geoquizz\application\actions\GameAction;
use api_geoquizz\application\actions\HomeAction;

return function (\Slim\App $app): \Slim\App {
    // Route d'accueil
    $app->get('/', HomeAction::class);

    // Routes pour le jeu
    $app->group('/games', function($app) {
        // Créer une nouvelle partie
        $app->post('', [GameAction::class, 'createGame']);
        
        // Récupérer une partie spécifique
        $app->get('/{id}', [GameAction::class, 'getGame']);
        
        // Démarrer une partie
        $app->patch('/{id}/start', [GameAction::class, 'startGame']);
        
        // Soumettre une réponse pour la photo courante
        $app->post('/{id}/answer', [GameAction::class, 'submitAnswer']);
        
        // Terminer une partie
        $app->patch('/{id}/end', [GameAction::class, 'endGame']);
        
        // Obtenir la photo courante d'une partie
        $app->get('/{id}/current-photo', [GameAction::class, 'getCurrentPhoto']);
        
        // Obtenir le score actuel d'une partie
        $app->get('/{id}/score', [GameAction::class, 'getScore']);
        
        // Obtenir le statut d'une partie
        $app->get('/{id}/status', [GameAction::class, 'getStatus']);
    });

    return $app;
};