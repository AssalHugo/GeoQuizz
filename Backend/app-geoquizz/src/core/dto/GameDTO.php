<?php
namespace api_geoquizz\core\dto;

use api_geoquizz\core\domain\entities\geoquizz\Game;

class GameDTO extends DTO
{
    protected string $id;
    protected string $userId;
    protected array $photoIds;
    protected string $serieId;
    protected int $score;
    protected string $state;
    protected int $currentPhotoIndex;
    protected ?\DateTimeImmutable $startTime;

    public function __construct(Game $game)
    {
        $this->id = $game->getId();
        $this->userId = $game->getUserId();
        $this->photoIds = $game->getPhotoIds();
        $this->serieId = $game->getSerieId();
        $this->score = $game->getScore();
        $this->state = $game->getState();
        $this->currentPhotoIndex = $game->getCurrentPhotoIndex();
        $this->startTime = $game->getStartTime();
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'photoIds' => $this->photoIds,
            'serieId' => $this->serieId,
            'score' => $this->score,
            'state' => $this->state,
            'currentPhotoIndex' => $this->currentPhotoIndex,
            'startTime' => $this->startTime?->format('c')
        ];
    }
    public function toEntity(): Game
    {
        $game = new Game();
        $game->setId($this->id)
            ->setUserId($this->userId)
            ->setPhotoIds($this->photoIds)
            ->setSerieId($this->serieId)
            ->setScore($this->score)
            ->setState($this->state)
            ->setCurrentPhotoIndex($this->currentPhotoIndex)
            ->setStartTime($this->startTime);

        return $game;
    }
}

