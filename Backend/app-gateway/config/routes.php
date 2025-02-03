<?php

declare(strict_types=1);

return function (\Slim\App $app): \Slim\App {

    $app->get('/', \gateway_geo\application\actions\HomeAction::class);

    return $app;
};
