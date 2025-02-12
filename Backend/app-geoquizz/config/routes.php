<?php
declare(strict_types=1);


use api_geoquizz\application\actions\GetGamesByUserAction;
use api_geoquizz\application\actions\GetHighestScoreBySerieForUserAction;
use Slim\App;
use api_geoquizz\application\actions\CreateGameAction;
use api_geoquizz\application\actions\GetCurrentPhotoAction;
use api_geoquizz\application\actions\GetGameByIdAction;
use api_geoquizz\application\actions\GetGamesAction;
use api_geoquizz\application\actions\HomeAction;
use api_geoquizz\application\actions\PlayGameAction;
use api_geoquizz\application\actions\StartGameAction;
use api_geoquizz\application\actions\CreateUserAction;
use api_geoquizz\application\actions\GetUserByEmailAction;
use api_geoquizz\application\actions\GetUserByIdAction;
use api_geoquizz\application\actions\GetNextPhotoAction;

return function (App $app): App {
    // Page d'accueil
    $app->get('/', HomeAction::class);

    // Routes pour les jeux
    $app->post('/games', CreateGameAction::class);
    $app->get('/games/{id}', GetGameByIdAction::class);
    $app->get('/dashboard', GetGamesAction::class);
    $app->patch('/games/{id}/start', StartGameAction::class);
    $app->post('/games/{id}/answer', PlayGameAction::class);
    $app->get('/games/{id}/next-photo', GetNextPhotoAction::class);
    $app->get('/games/{id}/current-photo', GetCurrentPhotoAction::class);


    // Routes pour les utilisateurs
    $app->post('/users', CreateUserAction::class);
    $app->get('/users/{id}', GetUserByIdAction::class);
    $app->get('/users', GetUserByEmailAction::class);
    $app->get('/users/{userId}/highest-score', GetHighestScoreBySerieForUserAction::class);
    $app->get('/users/{userId}/games', GetGamesByUserAction::class);

    return $app;
};
