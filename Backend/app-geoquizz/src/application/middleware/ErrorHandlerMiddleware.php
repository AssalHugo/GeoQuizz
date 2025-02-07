<?php

namespace api_geoquizz\application\middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;
use api_geoquizz\core\repositoryInterface\RepositoryEntityNotFoundException;
use api_geoquizz\core\repositoryInterface\RepositoryEntityConflictException;
use api_geoquizz\core\repositoryInterface\RepositoryEntityValidationException;
use api_geoquizz\application\renderer\JsonRenderer;
use Throwable;

class ErrorHandlerMiddleware
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(Request $request, Response $response, Throwable $exception): Response
    {
        $this->logger->error($exception->getMessage(), ['exception' => $exception]);

        $statusCode = 500;
        $errorMessage = "Internal Server Error";

        if ($exception instanceof HttpNotFoundException) {
            $statusCode = 404;
            $errorMessage = "Ressource non trouvée";
        } elseif ($exception instanceof HttpUnauthorizedException) {
            $statusCode = 401;
            $errorMessage = "Accès non autorisé";
        } elseif ($exception instanceof RepositoryEntityNotFoundException) {
            $statusCode = 404;
            $errorMessage = "L'entité demandée est introuvable";
        } elseif ($exception instanceof RepositoryEntityConflictException) {
            $statusCode = 409;
            $errorMessage = "Conflit d'entité : duplication de données";
        } elseif ($exception instanceof RepositoryEntityValidationException) {
            $statusCode = 400;
            $errorMessage = "Données invalides";
        }

        return JsonRenderer::render($response, $statusCode, [
            'error' => $errorMessage,
            'details' => $exception->getMessage(),
        ]);
    }
}
