<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

require_once __DIR__ . '/../vendor/autoload.php';

$isDevMode = true;
$proxyDir = null;
$config = ORMSetup::createAttributeMetadataConfiguration(
    [__DIR__ . "/../src/core/domain/entities"],
    $isDevMode
);

$connection = DriverManager::getConnection([
    'driver' => 'pdo_pgsql',
    'host' => getenv('DB_HOST'),
    'dbname' => getenv('DB_NAME'),
    'user' => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
    'port' => getenv('DB_PORT'),
], $config);

$entityManager = new EntityManager($connection, $config);

return $entityManager;