<?php

namespace api_geoquizz\core\domain\entities\seriesDirectus;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use api_geoquizz\core\dto\PhotoDTO;

#[ORM\Entity]
#[ORM\Table(name: 'Photo')]
class Photo
{
    #[ORM\Column(type: Types::INTEGER, length: 48)]
    private int $id;

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
    public function getId(): int
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

    // Setters
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;
        return $this;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function setSerie(Serie $serie): self
    {
        $this->serieId = $serie;
        return $this;
    }

    public function toDTO(): PhotoDTO
    {
        return new PhotoDTO($this);
    }
}
