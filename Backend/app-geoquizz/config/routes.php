<?php
declare(strict_types=1);


use api_geoquizz\application\actions\GetHighestScoreBySerieForUserAction;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;
use api_geoquizz\application\actions\CreateGameAction;
use api_geoquizz\application\actions\EndGameAction;
use api_geoquizz\application\actions\GetCurrentPhotoAction;
use api_geoquizz\application\actions\GetGameAction;
use api_geoquizz\application\actions\GetGameScoreAction;
use api_geoquizz\application\actions\GetGameStateAction;
use api_geoquizz\application\actions\HomeAction;
use api_geoquizz\application\actions\PlayGameAction;
use api_geoquizz\application\actions\StartGameAction;
use api_geoquizz\application\actions\CreateUserAction;
use api_geoquizz\application\actions\GetUserByEmailAction;
use api_geoquizz\application\actions\GetUserByIdAction;

return function (App $app): App {
    // Page d'accueil
    $app->get('/', HomeAction::class);

    // Routes pour les jeux
    $app->post('/games', CreateGameAction::class);
    $app->get('/games/{id}', GetGameAction::class);
    $app->patch('/games/{id}/start', StartGameAction::class);
    $app->post('/games/{id}/answer', PlayGameAction::class);
    $app->patch('/games/{id}/end', EndGameAction::class);
    $app->get('/games/{id}/current-photo', GetCurrentPhotoAction::class);
    $app->get('/games/{id}/score', GetGameScoreAction::class);
    $app->get('/games/{id}/state', GetGameStateAction::class);

    // Routes pour les utilisateurs
    $app->post('/users', CreateUserAction::class);
    $app->get('/users/{id}', GetUserByIdAction::class);
    $app->get('/users', GetUserByEmailAction::class);
    $app->get('/users/{userId}/series/{serieId}/highest-score', GetHighestScoreBySerieForUserAction::class);

    return $app;
};
