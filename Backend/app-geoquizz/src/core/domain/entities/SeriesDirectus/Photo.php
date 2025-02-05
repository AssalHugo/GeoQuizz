<?php

namespace api_geoquizz\core\domain\entities\SeriesDirectus;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use api_geoquizz\core\domain\entities\Entity;

class Photo extends Entity
{
    #[ORM\Column(type: Types::STRING, length: 48)]
    private string $id;

    #[ORM\Column(type: Types::STRING, length: 48)]
    private string $photo;

    #[ORM\Column(type: Types::DECIMAL, length: 48)]
    private float $latitude;

    #[ORM\Column(type: Types::DECIMAL, length: 48)]
    private float $longitude;

    #[ORM\Column(type: Types::STRING, length: 48)]
    private string $adresse;

    #[ORM\ManyToOne(inversedBy: "photos")]
    private Serie $serieId;

    // Getters
    public function getId(): string
    {
        return $this->id;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function getAdresse(): string
    {
        return $this->adresse;
    }

    public function getSerie(): Serie
    {
        return $this->serieId;
    }

    public function toDTO(): PhotoDTO
    {
        return new PhotoDTO($this);
    }
}
