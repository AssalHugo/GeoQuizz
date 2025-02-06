<?php

use gateway_geo\application\actions\GatewayLoginAction;
use gateway_geo\application\actions\GatewayPhotosAction;
use gateway_geo\application\actions\GatewayRefreshAction;
use gateway_geo\application\actions\GatewayRegisterAction;
use gateway_geo\application\actions\GatewaySeriesAction;
use gateway_geo\application\actions\GatewaySeriesByIdAction;
use gateway_geo\application\actions\GatewayPhotosByIdAction;
use gateway_geo\application\actions\GatewayGetGameAction;
use gateway_geo\application\actions\GatewayCreateGameAction;
use gateway_geo\application\actions\GatewayStartGameAction;
use gateway_geo\application\actions\GatewayPlayGameAction;
use gateway_geo\application\actions\GatewayEndGameAction;
use gateway_geo\application\actions\GatewayGetCurrentPhotoAction;
use gateway_geo\application\actions\GatewayGetGameScoreAction;
use gateway_geo\application\actions\GatewayGetGameStateAction;
use Psr\Container\ContainerInterface;

$settings = require __DIR__ . '/settings.php';

return
    [
        'settings' => $settings,

        'guzzle.client.geoquizz' => function ($c) {
            return new \GuzzleHttp\Client([
                'base_uri' => $c->get('settings')['geoquizz.api'],
            ]);
        },

        'guzzle.client.serieDirectus' => function ($c) {
            return new \GuzzleHttp\Client([
                'base_uri' => $c->get('settings')['serieDirectus.api'],
            ]);
        },

        'guzzle.client.auth' => function ($c) {
            return new \GuzzleHttp\Client([
                'base_uri' => $c->get('settings')['auth.api'],
            ]);
        },

        GatewaySeriesAction::class => function (ContainerInterface $c) {
            return new GatewaySeriesAction($c->get('guzzle.client.serieDirectus'));
        },

        GatewaySeriesByIdAction::class => function (ContainerInterface $c) {
            return new GatewaySeriesByIdAction($c->get('guzzle.client.serieDirectus'));
        },

        GatewayPhotosAction::class => function (ContainerInterface $c) {
            return new GatewayPhotosAction($c->get('guzzle.client.serieDirectus'));
        },

        GatewayPhotosByIdAction::class => function (ContainerInterface $c) {
            return new GatewayPhotosByIdAction($c->get('guzzle.client.serieDirectus'));
        },

        GatewayGetGameAction::class => function (ContainerInterface $c) {
            return new GatewayGetGameAction($c->get('guzzle.client.geoquizz'));
        },

        GatewayCreateGameAction::class => function (ContainerInterface $c) {
            return new GatewayCreateGameAction($c->get('guzzle.client.geoquizz'));
        },

        GatewayStartGameAction::class => function (ContainerInterface $c) {
            return new GatewayStartGameAction($c->get('guzzle.client.geoquizz'));
        },

        GatewayPlayGameAction::class => function (ContainerInterface $c) {
            return new GatewayPlayGameAction($c->get('guzzle.client.geoquizz'));
        },

        GatewayEndGameAction::class => function (ContainerInterface $c) {
            return new GatewayEndGameAction($c->get('guzzle.client.geoquizz'));
        },

        GatewayGetCurrentPhotoAction::class => function (ContainerInterface $c) {
            return new GatewayGetCurrentPhotoAction($c->get('guzzle.client.geoquizz'));
        },

        GatewayGetGameScoreAction::class => function (ContainerInterface $c) {
            return new GatewayGetGameScoreAction($c->get('guzzle.client.geoquizz'));
        },

        GatewayGetGameStateAction::class => function (ContainerInterface $c) {
            return new GatewayGetGameStateAction($c->get('guzzle.client.geoquizz'));
        },

        GatewayRegisterAction::class => function (ContainerInterface $c) {
            return new GatewayRegisterAction($c->get('guzzle.client.auth'));
        },

        GatewayLoginAction::class => function (ContainerInterface $c) {
            return new GatewayLoginAction($c->get('guzzle.client.auth'));
        },

        GatewayRefreshAction::class => function (ContainerInterface $c) {
            return new GatewayRefreshAction($c->get('guzzle.client.auth'));
        },
    ];
