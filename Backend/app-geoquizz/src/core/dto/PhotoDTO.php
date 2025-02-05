<?php

namespace api_geoquizz\core\dto;

use api_geoquizz\core\dto\DTO;
use api_geoquizz\core\domain\entities\seriesDirectus\Serie;
use api_geoquizz\core\domain\entities\seriesDirectus\Photo;

class PhotoDTO extends DTO
{
    protected int $id;
    protected string $photo;
    protected float $latitude;
    protected float $longitude;
    protected string $adresse;
    protected Serie $serie;


    public function __construct(Photo $p)
    {
        $this->id = $p->getId();
        $this->photo = $p->getPhoto();
        $this->latitude = $p->getLatitude();
        $this->longitude = $p->getLongitude();
        $this->adresse = $p->getAdresse();
        $this->serie = $p->getSerie();
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'photo' => $this->photo,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'adresse' => $this->adresse,
            'serie' => $this->serie
        ];
    }
}
