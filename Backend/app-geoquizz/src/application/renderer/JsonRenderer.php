<?php

namespace api_geoquizz\application\renderer;

use Psr\Http\Message\ResponseInterface as Response;

class JsonRenderer
{
    public static function render(Response $rs, int $code, mixed $data = null, string $message = ''): Response
    {
        $responseBody = [];

        // Si des données sont fournies, les inclure dans la réponse
        if ($data !== null) {
            $responseBody['data'] = $data;
        }

        // Si un message d'erreur est fourni, l'inclure dans la réponse
        if ($message) {
            $responseBody['message'] = $message;
        }

        // Ajouter un champ pour le code de statut
        $responseBody['status'] = $code;

        // Convertir le tableau en JSON et renvoyer la réponse
        $rs = $rs->withStatus($code)
            ->withHeader('Content-Type', 'application/json;charset=utf-8');
        $rs->getBody()->write(json_encode($responseBody));

        return $rs;
    }
}
