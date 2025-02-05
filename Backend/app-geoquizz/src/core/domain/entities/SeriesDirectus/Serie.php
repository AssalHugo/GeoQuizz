<?php

namespace api_geoquizz\core\domain\entities\SeriesDirectus;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use api_geoquizz\core\domain\entities\Entity;
use Doctrine\Common\Collections\Collection;

class Serie extends Entity
{
    #[ORM\Column(type: Types::STRING, length: 48)]
    private string $id;

    #[ORM\Column(type: Types::STRING, length: 48)]
    private string $titre;

    #[ORM\Column(type: Types::DECIMAL, length: 48)]
    private float $latitude;

    #[ORM\Column(type: Types::DECIMAL, length: 48)]
    private float $longitude;

    #[ORM\Column(type: Types::DECIMAL, length: 48)]
    private float $largeur;

    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: "serieId")]
    private Collection $photos;

    // Getters
    public function getId(): string
    {
        return $this->id;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function getLargeur(): float
    {
        return $this->largeur;
    }

    public function getPhotos(): array
    {
        return $this->photos;
    }

    public function toDTO(): SerieDTO
    {
        return new SerieDTO($this);
    }
}
