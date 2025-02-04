<?php

namespace api_geoquizz\core\domain\entities\geoquizz;

use api_geoquizz\core\domain\entities\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

class Game extends Entity {
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
    private string $status;

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

    public function getStatus(): string
    {
        return $this->status;
    }

    // Setters

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

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }
}