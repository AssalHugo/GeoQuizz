<?php

namespace api_geoquizz\core\services\serieDirectus;

use api_geoquizz\core\dto\SerieDTO;
use Doctrine\Common\Collections\Collection;

interface SerieDirectusInterface
{
    public function getSerieById(int $id): SerieDTO;
    public function getPhotoBySerie(int $idSerie): Collection;
    public function getAllSeries();
}
