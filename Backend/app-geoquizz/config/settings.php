<?php
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

return  [

    'displayErrorDetails' => true,
    'logs.dir' => __DIR__ . '/../var/logs',

    'directus.api' => 'http://api.directus:8055/',

    'jwt_secret' => $_ENV['JWT_SECRET'] ?? 'default_secret'  
];
