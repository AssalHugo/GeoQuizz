<?php

namespace api_geoquizz\core\domain\entities\geoquizz;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

class Game {
    #[ORM\Column(type: Types::STRING, length: 48)]
    private string $id;

    #[ORM\Column(type: Types::STRING, length: 48)]
    private string $userId;

    #[ORM\Column(type: Types::STRING, length: 48)]
    private array $photoIds;

    #[ORM\Column(type: Types::STRING, length: 48)]
    private string $serieId;

    #[ORM\Column(type: Types::STRING, length: 48)]

    private int $score = 0;

    #[ORM\Column(type: Types::STRING, length: 48)]
    private string $state;

    // Getters
    public function getId(): string
    {
        return $this->id;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getPhotoIds(): array
    {
        return $this->photoIds;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function getState(): string
    {
        return $this->state;
    }

    // Setters

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }
    public function setUserId(string $userId): self
    {
        $this->userId = $userId;
        return $this;
    }
    public function setSerie(string $serieId): self
    {
        $this->serieId = $serieId;
        return $this;
    }
    public function setPhotoIds(array $photoIds): self
    {
        $this->photoIds = $photoIds;
        return $this;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;
        return $this;
    }

    public function setState(string $state): self
    {
        $this->state = $state;
        return $this;
    }
}