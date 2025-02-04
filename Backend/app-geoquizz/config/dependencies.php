<?php

use api_geoquizz\core\services\serieDirectus\SerieDirectusInterface;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use api_geoquizz\infrastructure\adaptaters\SerieDirectusServiceAdapter;

$settings = require __DIR__ . '/settings.php';

return [

    'settings' => $settings,

    'guzzle.client.serieDirectus' => function (ContainerInterface $container) {
        return new \GuzzleHttp\Client([
            'base_uri' => $container->get('settings')['serieDirectus.api']
        ]);
    },

    EntityManager::class => function (ContainerInterface $container) {
        return require __DIR__ . '/doctrine.php';
    },

    SerieDirectusInterface::class => function (ContainerInterface $container) {
        return new SerieDirectusServiceAdapter(
            $container->get('guzzle.client.serieDirectus')
        );
    }

];
