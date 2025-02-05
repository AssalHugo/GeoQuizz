<?php

declare(strict_types=1);

return function (\Slim\App $app): \Slim\App {

    $app->get('/', \app_auth\application\actions\HomeAction::class);
    $app->post('/auth/signin', \app_auth\application\actions\SignInAction::class);
    $app->post('/auth/register', \app_auth\application\actions\RegisterAction::class);
    $app->post('/auth/refresh', \app_auth\application\actions\RefreshAction::class);
    $app->post('/tokens/validate', \app_auth\application\actions\ValidateTokenAction::class);

    return $app;
};
