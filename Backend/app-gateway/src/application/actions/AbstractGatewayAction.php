<?php

namespace gateway_geo\application\actions;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractGatewayAction
{

    public ClientInterface $remote;

    function __construct(ClientInterface $client)
    {
        $this->remote = $client;
    }

    abstract public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface;
}
