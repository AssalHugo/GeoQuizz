<?php

use api_geoquizz\application\actions\CreateGameAction;
use api_geoquizz\application\actions\CreateUserAction;
use api_geoquizz\application\actions\GetUserByEmailAction;
use api_geoquizz\application\actions\GetUserByIdAction;
use api_geoquizz\core\services\seriesDirectus\SerieDirectusInterface;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use api_geoquizz\core\services\user\UserService;
use api_geoquizz\core\services\user\UserServiceInterface;
use api_geoquizz\core\repositoryInterface\user\UserRepositoryInterface;
use api_geoquizz\infrastructure\repositories\user\UserRepository;
use api_geoquizz\infrastructure\adaptaters\SerieDirectusServiceAdapter;
use api_geoquizz\core\services\GameService;
use api_geoquizz\core\services\GameServiceInterface;
use api_geoquizz\infrastructure\repositories\GameRepository;

$settings = require __DIR__ . '/settings.php';

return [

    'settings' => $settings,

    'guzzle.client.gateway' => function (ContainerInterface $container) {
        return new \GuzzleHttp\Client([
            'base_uri' => $container->get('settings')['gateway.api']
        ]);
    },

    EntityManager::class => function (ContainerInterface $container) {
        return require __DIR__ . '/doctrine.php';
    },

    UserRepositoryInterface::class => function (ContainerInterface $container) {
        return new UserRepository($container->get(EntityManager::class));
    },

    UserServiceInterface::class => function (ContainerInterface $container) {
        return new UserService($container->get(UserRepositoryInterface::class));
    },

    GetUserByIdAction::class => function (ContainerInterface $container) {
        return new GetUserByIdAction($container->get(UserServiceInterface::class));
    },

    GetUserByEmailAction::class => function (ContainerInterface $container) {
        return new GetUserByEmailAction($container->get(UserServiceInterface::class));
    },

    CreateUserAction::class => function (ContainerInterface $container) {
        return new CreateUserAction($container->get(UserServiceInterface::class));
    },

    SerieDirectusInterface::class => function (ContainerInterface $container) {
        return new SerieDirectusServiceAdapter(
            $container->get('guzzle.client.gateway')
        );
    },

    CreateGameAction::class => function (ContainerInterface $container) {
        return new CreateGameAction($container->get(GameServiceInterface::class));
    },

    GameService::class => function (ContainerInterface $container) {
        return new GameService(
            $container->get(GameRepository::class),
            $container->get(SerieDirectusInterface::class)
        );
    }
];
