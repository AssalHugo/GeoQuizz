<?php

namespace api_geoquizz\core\domain\services;

use api_geoquizz\core\domain\entities\Game;

interface GameServiceInterface {

    public function createGame(string $serie, string $userId): Game;


}