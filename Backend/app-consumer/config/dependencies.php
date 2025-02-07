<?php

use Psr\Container\ContainerInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use app_consumer\core\services\mails\ServiceMail;
use app_consumer\core\services\mails\ServiceMailInterface;

$settings = require __DIR__ . '/settings.php';

return
    [
        'settings' => $settings,

        'mailer.config' => function (ContainerInterface $c) {
            return parse_ini_file(__DIR__ . '/mailer.ini', true)['mailer'];
        },

        ServiceMailInterface::class => function (ContainerInterface $c) {
            $config = $c->get('mailer.config');
            $transport = Transport::fromDsn($config['dsn']);
            $mailer = new Mailer($transport);
            return new ServiceMail($mailer, $config['from']);
        },
    ];
