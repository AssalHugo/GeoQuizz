<?php

declare(strict_types=1);


return function (\Slim\App $app): \Slim\App {

    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });

    // Requête vers le microservice Auth
    $app->post('/auth/signin', \gateway_geo\application\actions\GatewaySignInAction::class);
    $app->post('/auth/register', \gateway_geo\application\actions\GatewayRegisterAction::class);
    $app->post('/auth/refresh', \gateway_geo\application\actions\GatewayRefreshAction::class);

    // Requête vers le microservice Directus
    $app->get('/series', \gateway_geo\application\actions\GatewaySeriesAction::class);
    $app->get('/series/{id}', \gateway_geo\application\actions\GatewaySeriesByIdAction::class);

    // Requête vers le microservice GeoQuizz
    $app->get('/games/{id}', \gateway_geo\application\actions\GatewayGetGameAction::class);
    $app->post('/games', \gateway_geo\application\actions\GatewayCreateGameAction::class);
    $app->patch('/games/{id}/start', \gateway_geo\application\actions\GatewayStartGameAction::class);
    $app->post('/games/{id}/answer', \gateway_geo\application\actions\GatewayPlayGameAction::class);
    $app->get('/games/{id}/next-photo', \gateway_geo\application\actions\GatewayGetNextPhotoAction::class);
    $app->get('/games/{id}/current-photo', \gateway_geo\application\actions\GatewayGetCurrentPhotoAction::class);
    $app->get('/users/{userId}/games', \gateway_geo\application\actions\GetGamesByUserAction::class);
    $app->get('/users/{userId}/highest-score', \gateway_geo\application\actions\GatewayGetHighestScoreBySerieForUserAction::class);

    return $app;
};
