<?php

declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

return function (\Slim\App $app): \Slim\App {

    $app->get('/', \api_geoquizz\application\actions\HomeAction::class);
    $app->post('/users', \api_geoquizz\application\actions\CreateUserAction::class);
    $app->get('/users/{id}', \api_geoquizz\application\actions\GetUserByIdAction::class);
    $app->get('/users', \api_geoquizz\application\actions\GetUserByEmailAction::class);

    return $app;
};
