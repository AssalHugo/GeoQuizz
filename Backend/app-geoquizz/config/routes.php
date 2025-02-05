<?php
declare(strict_types=1);

use api_geoquizz\application\actions\CreateGameAction;
use api_geoquizz\application\actions\EndGameAction;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use api_geoquizz\application\actions\GameAction;
use api_geoquizz\application\actions\GetCurrentPhotoAction;
use api_geoquizz\application\actions\GetGameAction;
use api_geoquizz\application\actions\GetGameScoreAction;
use api_geoquizz\application\actions\GetGameStateAction;
use api_geoquizz\application\actions\HomeAction;
use api_geoquizz\application\actions\PlayGameAction;
use api_geoquizz\application\actions\StartGameAction;

return function (\Slim\App $app): \Slim\App {
    // Route d'accueil
    $app->get('/', HomeAction::class);

    // Routes pour le jeu
    $app->group('/games', function($app) {
        // Créer une nouvelle partie
        $app->post('', [CreateGameAction::class, 'createGame']);
        
        // Récupérer une partie spécifique
        $app->get('/{id}', [GetGameAction::class, 'getGame']);
        
        // Démarrer une partie
        $app->patch('/{id}/start', [StartGameAction::class, 'startGame']);
        
        // Soumettre une réponse pour la photo courante
        $app->post('/{id}/answer', [PlayGameAction::class, 'submitAnswer']);
        
        // Terminer une partie
        $app->patch('/{id}/end', [EndGameAction::class, 'endGame']);
        
        // Obtenir la photo courante d'une partie
        $app->get('/{id}/current-photo', [GetCurrentPhotoAction::class, 'getCurrentPhoto']);
        
        // Obtenir le score actuel d'une partie
        $app->get('/{id}/score', [GetGameScoreAction::class, 'getScore']);
        
        // Obtenir l etat d'une partie
        $app->get('/{id}/state', [GetGameStateAction::class, 'getStatus']);
    });
    $app->post('/users', \api_geoquizz\application\actions\CreateUserAction::class);
    $app->get('/users/{id}', \api_geoquizz\application\actions\GetUserByIdAction::class);
    $app->get('/users', \api_geoquizz\application\actions\GetUserByEmailAction::class);

    return $app;
};