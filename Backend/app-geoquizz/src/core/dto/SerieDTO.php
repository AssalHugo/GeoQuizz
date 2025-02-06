<?php

namespace api_geoquizz\core\dto;

use api_geoquizz\core\dto\DTO;
use api_geoquizz\core\domain\entities\seriesDirectus\Serie;
use api_geoquizz\core\domain\entities\seriesDirectus\Photo;
use Doctrine\Common\Collections\Collection;

class SerieDTO extends DTO
{
    protected int $id;
    protected string $titre;
    protected float $latitude;
    protected float $longitude;
    protected float $largeur;
    protected Collection $photos;


    public function __construct(Serie $s)
    {
        $this->id = $s->getId();
        $this->titre = $s->getTitre();
        $this->latitude = $s->getLatitude();
        $this->longitude = $s->getLongitude();
        $this->largeur = $s->getLargeur();
        $this->photos = $s->getPhotos();
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'largeur' => $this->largeur,
            'photos' => $this->photos
        ];
    }
}
