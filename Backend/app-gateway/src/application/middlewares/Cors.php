<?php

namespace gateway_geo\application\middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Cors implements MiddlewareInterface
{
    public function process(ServerRequestInterface $rq, RequestHandlerInterface $handler): ResponseInterface
    {
        $origin = $rq->hasHeader('Origin') ? $rq->getHeaderLine('Origin') : '*';
        $response = $handler->handle($rq);

        $response = $response
            ->withHeader('Access-Control-Allow-Origin', $origin)
            ->withHeader('Access-Control-Allow-Methods', 'POST, PUT, GET, DELETE, HEAD, PATCH, OPTIONS')
            ->withHeader('Access-Control-Allow-Headers', 'Authorization, Content-Type')
            ->withHeader('Access-Control-Max-Age', 3600)
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withHeader('Content-Language', 'fr-FR')
            ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->withHeader('Pragma', 'no-cache')
            ->withHeader('Expires', '0');

        return $response;
    }
}
