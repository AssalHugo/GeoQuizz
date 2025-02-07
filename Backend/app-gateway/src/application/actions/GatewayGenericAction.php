<?php

namespace gateway_geo\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\Exception\RequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Response;

class GatewayGenericAction extends AbstractGatewayAction
{
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $uri = $rq->getUri()->getPath();

        $method = $rq->getMethod();
        $options = [
            'headers' => $rq->getHeaders(),
            'query' => $rq->getQueryParams(),
            'body' => $rq->getBody()->getContents()
        ];

        if ((str_contains($uri, 'series') || str_contains($uri, 'photos')) && !str_contains($uri, 'users')) {
            $uri = '/items' . $uri;

            $tokenDirectus = parse_ini_file('/var/php/../../tokenDirectus.ini')['TOKEN_DIRECTUS'];

            $options = [
                'headers' => $rq->getHeaders(),
                'query' => $rq->getQueryParams(),
                'body' => $rq->getBody()->getContents()
            ];

            $options['headers']['Authorization'] = 'Bearer ' . $tokenDirectus;
        }

        try {
            $response = $this->remote->request($method, $uri, $options);
        } catch (RequestException $e) {
            throw match ($e->getCode()) {
                404 => new HttpNotFoundException($rq, $e->getMessage()),
                403 => new HttpForbiddenException($rq, $e->getMessage()),
                400 => new HttpBadRequestException($rq, $e->getMessage()),
                default => new HttpInternalServerErrorException($rq, $e->getMessage()),
            };
        }
        return $response;
    }
}
