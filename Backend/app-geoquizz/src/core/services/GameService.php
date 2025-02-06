<?php

namespace api_geoquizz\core\services;

use api_geoquizz\core\domain\entities\geoquizz\Game;
use api_geoquizz\core\domain\entities\seriesDirectus\Photo;
use api_geoquizz\core\domain\entities\seriesDirectus\Serie;
use api_geoquizz\core\dto\GameDTO;
use api_geoquizz\core\repositoryInterface\GameRepositoryInterface;
use api_geoquizz\core\services\seriesDirectus\SerieDirectusInterface;
use Ramsey\Uuid\Uuid;

class GameService implements GameServiceInterface
{
    private GameRepositoryInterface $gameRepository;
    private SerieDirectusInterface $serieService;

    public function __construct(
        GameRepositoryInterface $gameRepository,
        SerieDirectusInterface $serieService
    ) {
        $this->gameRepository = $gameRepository;
        $this->serieService = $serieService;
    }

    public function getGameById(string $gameId): ?GameDTO
    {
        return $this->gameRepository->findById($gameId)->toDTO();
    }

    public function createGame(string $serieId, string $userId): GameDTO
    {
        $game = new Game();
        $serie = $this->serieService->getSerieById($serieId);
        $photos = $serie->photos->toArray();
        shuffle($photos);
        $photoIds = array_slice(array_map(fn($photo) => $photo->getId(), $photos), 0, 10);

        $game->setId(Uuid::uuid4()->toString())
            ->setUserId($userId)
            ->setSerieId($serieId)
            ->setPhotoIds($photoIds)
            ->setState('CREATED')
            ->setScore(0)
            ->setCurrentPhotoIndex(1);

        $this->gameRepository->save($game);
        return $game->toDTO();
    }

    public function isFinished(GameDTO $game): bool
    {
        return $game->currentPhotoIndex >= count($game->photoIds);
    }

    public function startGame(GameDTO $game): void
    {
        $game->state ='IN_PROGRESS';
        $game->startTime = (new \DateTimeImmutable());
        $this->gameRepository->save($game->toEntity());
    }

    public function calculateScore(GameDTO $game, float $distance, float $responseTime): int
    {
        $points = 0;
        if ($distance < 100) $points = 5;
        elseif ($distance < 200) $points = 3;
        elseif ($distance < 300) $points = 1;

        $multiplier = 1;
        if ($responseTime < 5) $multiplier = 4;
        elseif ($responseTime < 10) $multiplier = 2;
        elseif ($responseTime > 20) $multiplier = 0;

        return $points * $multiplier;
    }

    public function updateGameProgress(GameDTO $game, float $latitude, float $longitude): int
{
    $currentPhoto = $this->getCurrentPhoto($game);
    if (!$currentPhoto) {
        throw new \Exception("Aucune photo actuelle trouvée.");
    }

    $distance = $this->calculateDistance(
        $latitude,
        $longitude,
        $currentPhoto->getLatitude(),
        $currentPhoto->getLongitude()
    );

    $startTime = $game->startTime ?? new \DateTimeImmutable();
    $game->startTime = $startTime;

    $responseTime = time() - $startTime->getTimestamp();
    $score = $this->calculateScore($game, $distance, $responseTime);

    $game->score += $score;
    $game->currentPhotoIndex++;

    if ($this->isFinished($game)) {
        $this->endGame($game);
    }

    $this->gameRepository->save($game->toEntity());
    return $score;
}

    

    public function endGame(GameDTO $game): void
    {
        $game->state= 'FINISHED';
        $this->gameRepository->save($game->toEntity());
    }

    public function saveGameResult(GameDTO $game): bool
    {
        try {
            $this->gameRepository->save($game->toEntity());
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getGamePhotos(GameDTO $game): array
    {
        $photos = [];
        foreach ($game->photoIds as $photoId) {
            $photos[] = $this->serieService->getPhotoBySerie($game->serieId)
                ->filter(fn($photo) => $photo->getId() === $photoId)
                ->first();
        }
        return $photos;
    }

    public function getCurrentPhoto(GameDTO $game): ?Photo
    {
        $photoIds = $game->photoIds;
        $currentPhotoIndex = $game->currentPhotoIndex;
    
        error_log("PhotoIds: " . json_encode($photoIds));
        error_log("Current Photo Index: " . $currentPhotoIndex);
    
        if (empty($photoIds) || !isset($photoIds[$currentPhotoIndex])) {
            error_log("No valid photo ID found for the current index.");
            return null;
        }
    
        $photoId = $photoIds[$currentPhotoIndex];
        error_log("Fetching photo with ID: " . $photoId);
    
        $photo = $this->serieService->getPhotoBySerie($game->serieId)
            ->filter(fn($photo) => $photo->getId() === $photoId)
            ->first();
    
        if (!$photo) {
            error_log("No photo found with ID: " . $photoId);
            return null;
        }
    
        error_log("Photo found: " . var_export($photo, true));
        return $photo;
    }
    
    public function getGameState(GameDTO $game): string
    {
        return $game->state;
    }

    private function calculateDistance(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
        $earthRadius = 6371000;
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        $a = sin($dlat / 2) * sin($dlat / 2) +
            cos($lat1) * cos($lat2) *
            sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
