<?php

namespace api_geoquizz\core\services;

use api_geoquizz\core\domain\entities\geoquizz\Game;

interface GameServiceInterface {

    public function createGame(string $serie, string $userId): Game;


}