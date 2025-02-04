<?php

namespace api_geoquizz\core\domain\entities\seriesDirectus;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use api_geoquizz\core\dto\SerieDTO;

class Serie
{
    #[ORM\Column(type: Types::INTEGER, length: 48)]
    private int $id;

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
    public function getId(): int
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

    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    // Setters
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
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

    public function setLargeur(float $largeur): self
    {
        $this->largeur = $largeur;
        return $this;
    }

    public function setPhotos(Collection $photos): self
    {
        $this->photos = $photos;
        return $this;
    }

    public function toDTO(): SerieDTO
    {
        return new SerieDTO($this);
    }
}
