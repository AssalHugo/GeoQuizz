<?php

use Psr\Container\ContainerInterface;
use app_auth\application\actions\SignInAction;
use app_auth\core\repositoryInterfaces\AuthRepositoryInterface;
use app_auth\core\services\auth\ServiceAuthInterface;
use app_auth\infrastructure\repositories\PDOAuthRepository;
use app_auth\core\services\auth\ServiceAuth;
use app_auth\application\providers\auth\JWTManager;
use app_auth\application\providers\auth\JWTAuthProvider;
use app_auth\application\actions\RefreshAction;
use app_auth\application\actions\RegisterAction;
use app_auth\application\actions\ValidateTokenAction;
use app_auth\core\services\geoquizz\ServiceGeoquizzInterface;
use app_auth\infrastructure\adaptaters\ServiceGeoquizzAdapter;

$settings = require __DIR__ . '/settings.php';

return [

    'settings' => $settings,

    'auth.pdo' => function (ContainerInterface $c) {
        $config = parse_ini_file(__DIR__ . '/auth.db.ini');
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";
        $user = $config['username'];
        $password = $config['password'];
        return new PDO($dsn, $user, $password);
    },

    'guzzle.client.geoquizz' => function (ContainerInterface $c) {
        return new \GuzzleHttp\Client([
            'base_uri' => $c->get('settings')['geoquizz.api']
        ]);
    },

    AuthRepositoryInterface::class => function (ContainerInterface $c) {
        return new PDOAuthRepository($c->get('auth.pdo'), $c->get(ServiceGeoquizzInterface::class));
    },

    JWTManager::class => function (ContainerInterface $c) {
        return new JWTManager(getenv('JWT_SECRET_KEY'), 'HS512');
    },

    ServiceAuthInterface::class => function (ContainerInterface $c) {
        return new ServiceAuth($c->get(AuthRepositoryInterface::class));
    },

    JWTAuthProvider::class => function (ContainerInterface $c) {
        return new JWTAuthProvider($c->get(ServiceAuthInterface::class), $c->get(JWTManager::class));
    },

    SignInAction::class => function (ContainerInterface $c) {
        return new SignInAction($c->get(JWTAuthProvider::class));
    },

    RefreshAction::class => function (ContainerInterface $c) {
        return new RefreshAction($c->get(JWTAuthProvider::class));
    },

    RegisterAction::class => function (ContainerInterface $c) {
        return new RegisterAction($c->get(JWTAuthProvider::class), $c->get(ServiceGeoquizzInterface::class));
    },

    ValidateTokenAction::class => function (ContainerInterface $c) {
        return new ValidateTokenAction($c->get(JWTAuthProvider::class));
    },

    ServiceGeoquizzInterface::class => function (ContainerInterface $c) {
        return new ServiceGeoquizzAdapter($c->get('guzzle.client.geoquizz'));
    },

];
