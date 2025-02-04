<?php

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

return [
    EntityManager::class => function (ContainerInterface $container) {
        return require __DIR__ . '/doctrine.php';
    },
];
