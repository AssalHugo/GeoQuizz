<?php

declare(strict_types=1);

return function (\Slim\App $app): \Slim\App {

    $app->get('/', \gateway_geo\application\actions\HomeAction::class);

    $app->get('/series', \gateway_geo\application\actions\GatewaySeriesAction::class);

    $app->get('/series/{id}', \gateway_geo\application\actions\GatewaySeriesByIdAction::class);

    $app->get('/photos', \gateway_geo\application\actions\GatewayPhotosAction::class);

    $app->get('/photos/{id}', \gateway_geo\application\actions\GatewayPhotosByIdAction::class);

    $app->get('/games/{id}', \gateway_geo\application\actions\GatewayGetGameAction::class);

    return $app;
};
