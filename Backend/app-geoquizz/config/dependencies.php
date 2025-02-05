<?php

use api_geoquizz\application\actions\CreateUserAction;
use api_geoquizz\application\actions\GetUserByEmailAction;
use api_geoquizz\application\actions\GetUserByIdAction;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use api_geoquizz\core\services\user\UserService;
use api_geoquizz\core\services\user\UserServiceInterface;
use api_geoquizz\core\repositoryInterface\user\UserRepositoryInterface;
use api_geoquizz\infrastructure\repositories\user\UserRepository;

return [
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
];
