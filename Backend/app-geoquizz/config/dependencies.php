<?php

use api_geoquizz\application\actions\CreateGameAction;
use api_geoquizz\application\actions\CreateUserAction;
use api_geoquizz\application\actions\GetGamesAction;
use api_geoquizz\application\actions\GetGameByIdAction;
use api_geoquizz\application\actions\GetNextPhotoAction;
use api_geoquizz\application\actions\GetUserByEmailAction;
use api_geoquizz\application\actions\GetUserByIdAction;
use api_geoquizz\application\actions\PlayGameAction;
use api_geoquizz\application\actions\StartGameAction;
use api_geoquizz\application\providers\JWTGameManager;
use api_geoquizz\application\providers\JWTGameProviderInterface;
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
use api_geoquizz\core\repositoryInterface\GameRepositoryInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$settings = require __DIR__ . '/settings.php';

return [

    'settings' => $settings,

    'guzzle.client.directus' => function (ContainerInterface $container) {
        return new \GuzzleHttp\Client([
            'base_uri' => $container->get('settings')['directus.api']
        ]);
    },

    'rabbitmq' => function (ContainerInterface $c) {
        $connection = new AMQPStreamConnection(
            $c->get('settings')['rabbitmq.host'],
            $c->get('settings')['rabbitmq.port'],
            $c->get('settings')['rabbitmq.user'],
            $c->get('settings')['rabbitmq.password']
        );
        $channel = $connection->channel();
        $channel->exchange_declare(
            $c->get('settings')['game.event.exchange'],
            $c->get('settings')['game.type.exchange'],
            false,
            true,
            false
        );
        $channel->queue_declare(
            $c->get('settings')['game.queue'],
            false,
            true,
            false,
            false
        );
        $channel->queue_bind(
            $c->get('settings')['game.queue'],
            $c->get('settings')['game.event.exchange'],
            $c->get('settings')['game.routing.key']
        );
        return $connection;
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
            $container->get('guzzle.client.directus')
        );
    },
    GameRepositoryInterface::class => function (ContainerInterface $container) {
        return new GameRepository($container->get(EntityManager::class));
    },
    JWTGameManager::class => function (ContainerInterface $container) {
        return new JWTGameManager(getenv('JWT_GAME_SECRET_KEY'), 'HS512');
    },
    GameServiceInterface::class => function (ContainerInterface $container) {
        return new GameService($container->get(GameRepositoryInterface::class), $container->get(SerieDirectusInterface::class), $container->get(JWTGameManager::class));
    },
    GameService::class => function (ContainerInterface $container) {
        return new GameService(
            $container->get(GameRepositoryInterface::class),
            $container->get(SerieDirectusInterface::class),
            $container->get(JWTGameManager::class)
        );
    },
    CreateGameAction::class => function (ContainerInterface $container) {
        return new CreateGameAction($container->get(GameServiceInterface::class));
    },
    GetGamesAction::class => function (ContainerInterface $container) {
        return new GetGamesAction($container->get(GameServiceInterface::class));
    },
    GetGameByIdAction::class => function (ContainerInterface $container) {
        return new GetGameByIdAction($container->get(GameServiceInterface::class));
    },
    PlayGameAction::class => function (ContainerInterface $container) {
        return new PlayGameAction($container->get(GameServiceInterface::class));
    },
    StartGameAction::class => function (ContainerInterface $container) {
        return new StartGameAction($container->get(GameServiceInterface::class));
    },
    GetNextPhotoAction::class => function (ContainerInterface $container) {
        return new GetNextPhotoAction($container->get(GameServiceInterface::class));
    }

];
