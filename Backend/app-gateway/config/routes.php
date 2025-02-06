<?php

declare(strict_types=1);

return function (\Slim\App $app): \Slim\App {

    $app->get('/', \gateway_geo\application\actions\HomeAction::class);
    $app->get('/series', \gateway_geo\application\actions\GatewaySeriesAction::class);
    $app->get('/series/{id}', \gateway_geo\application\actions\GatewaySeriesByIdAction::class);
    $app->get('/photos', \gateway_geo\application\actions\GatewayPhotosAction::class);
    $app->get('/photos/{id}', \gateway_geo\application\actions\GatewayPhotosByIdAction::class);
    $app->get('/games/{id}', \gateway_geo\application\actions\GatewayGetGameAction::class);
    $app->post('/games', \gateway_geo\application\actions\GatewayCreateGameAction::class);
    $app->patch('/games/{id}/start', \gateway_geo\application\actions\GatewayStartGameAction::class);
    $app->post('/games/{id}/answer', \gateway_geo\application\actions\GatewayPlayGameAction::class);
    $app->patch('/games/{id}/end', \gateway_geo\application\actions\GatewayEndGameAction::class);
    $app->get('/games/{id}/current-photo', \gateway_geo\application\actions\GatewayGetCurrentPhotoAction::class);
    $app->get('/games/{id}/score', \gateway_geo\application\actions\GatewayGetGameScoreAction::class);
    $app->get('/games/{id}/state', \gateway_geo\application\actions\GatewayGetGameStateAction::class);

    return $app;
};
