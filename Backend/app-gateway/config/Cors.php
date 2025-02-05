<?php

namespace gateway_geo\application\middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;
use Slim\Psr7\Response;

class Cors {
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeContext = RouteContext::fromRequest($request);
        $routingResults = $routeContext->getRoutingResults();
        $methods = $routingResults->getAllowedMethods();
        $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');

        $origin = $request->hasHeader('Origin') ? $request->getHeaderLine('Origin') : '*';

        if ($request->getMethod() === 'OPTIONS') {
            $response = new Response();
            return $response
                ->withHeader('Access-Control-Allow-Origin', $origin)
                ->withHeader('Access-Control-Allow-Methods', implode(', ', $methods))
                ->withHeader('Access-Control-Allow-Headers', $requestHeaders)
                ->withHeader('Access-Control-Allow-Credentials', 'true')
                ->withHeader('Access-Control-Max-Age', '3600');
        }

        $response = $handler->handle($request);

        return $response
            ->withHeader('Access-Control-Allow-Origin', $origin)
            ->withHeader('Access-Control-Allow-Methods', implode(', ', $methods))
            ->withHeader('Access-Control-Allow-Headers', $requestHeaders)
            ->withHeader('Access-Control-Allow-Credentials', 'true');
    }
}