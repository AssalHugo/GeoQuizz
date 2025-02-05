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

$dbconfig = parse_ini_file(__DIR__ . '/geoquizz.db.ini');

$connection = DriverManager::getConnection([
    'driver' => 'pdo_pgsql',
    'host' => $dbconfig['host'],
    'dbname' => $dbconfig['database'],
    'user' => $dbconfig['username'],
    'password' => $dbconfig['password'],
    'port' => $dbconfig['port'],
], $config);

$entityManager = new EntityManager($connection, $config);

return $entityManager;