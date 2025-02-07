<?php

return  [

    'displayErrorDetails' => true,
    'logs.dir' => __DIR__ . '/../var/logs',

    'directus.api' => 'http://api.directus:8055/',

    'game.event.exchange' => 'game.exchange',
    'game.type.exchange' => 'direct',
    'game.queue' => 'game.queue',
    'game.routing.key' => 'game.key',

    'rabbitmq.host' => 'rabbitmq',
    'rabbitmq.port' => 5672,
    'rabbitmq.user' => 'admin',
    'rabbitmq.password' => 'root',
];
