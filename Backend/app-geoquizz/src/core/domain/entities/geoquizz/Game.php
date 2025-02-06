<?php

namespace api_geoquizz\core\domain\entities\geoquizz;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use api_geoquizz\core\dto\GameDTO;

#[ORM\Entity]
#[ORM\Table(name: '"game"')]
class Game
{

    #[ORM\Id]
    #[ORM\Column(name: '"id"', type: Types::STRING, length: 48)]
    private string $id;

    #[ORM\Column(name: '"userId"', type: Types::GUID)]
    private string $userId;

    #[ORM\Column(name: '"photoIds"', type: Types::JSON)]
    private array $photoIds;

    #[ORM\Column(name: '"serieId"', type: Types::STRING, length: 48)]
    private string $serieId;

    #[ORM\Column(name: '"score"', type: Types::INTEGER)]
    private int $score = 0;

    #[ORM\Column(name: '"state"', type: Types::STRING, length: 20)]
    private string $state;

    #[ORM\Column(name: '"currentPhotoIndex"', type: Types::INTEGER)]
    private int $currentPhotoIndex = 0;

    #[ORM\Column(name: '"startTime"',type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $startTime = null;

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
    public function getSerieId(): string
    {
        return $this->serieId;
    }
    public function getScore(): int
    {
        return $this->score;
    }
    public function getState(): string
    {
        return $this->state;
    }
    public function getCurrentPhotoIndex(): int
    {
        return $this->currentPhotoIndex;
    }
    public function getStartTime(): ?\DateTimeImmutable
    {
        return $this->startTime;
    }

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

    public function setSerieId(string $serieId): self
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

    public function setCurrentPhotoIndex(int $index): self
    {
        $this->currentPhotoIndex = $index;
        return $this;
    }

    public function setStartTime(\DateTimeImmutable $time): self
    {
        $this->startTime = $time;
        return $this;
    }
    public function toDTO()
    {
        return new GameDTO($this);
    }
}
