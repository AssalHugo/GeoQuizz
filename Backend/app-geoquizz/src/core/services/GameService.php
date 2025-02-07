<?php

namespace api_geoquizz\core\services;

use api_geoquizz\core\domain\entities\geoquizz\Game;
use api_geoquizz\core\domain\entities\seriesDirectus\Photo;
use api_geoquizz\core\domain\entities\seriesDirectus\Serie;
use api_geoquizz\core\dto\GameDTO;
use api_geoquizz\core\repositoryInterface\GameRepositoryInterface;
use api_geoquizz\core\services\seriesDirectus\SerieDirectusInterface;
use Ramsey\Uuid\Uuid;

use function PHPUnit\Framework\throwException;

class GameService implements GameServiceInterface
{
    private GameRepositoryInterface $gameRepository;
    private SerieDirectusInterface $serieService;

    public function __construct(
        GameRepositoryInterface $gameRepository,
        SerieDirectusInterface  $serieService
    ) {
        $this->gameRepository = $gameRepository;
        $this->serieService = $serieService;
    }

    public function getGames(): array
    {
        $games = $this->gameRepository->findAll();
        return array_map(fn($games) => $games->toDTO(), $games);
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
        $game->state = 'IN_PROGRESS';
        $game->startTime = (new \DateTimeImmutable());
        $this->gameRepository->save($game->toEntity());
    }

    public function calculateScore(GameDTO $game, float $distance, float $responseTime): int
    {
        $distanceInMeters = $distance;

        $points = match (true) {
            $distanceInMeters < 100    => 10,   // Moins de 100m : parfait
            $distanceInMeters < 500    => 8,    // Moins de 500m
            $distanceInMeters < 1000   => 6,    // Moins de 1km
            $distanceInMeters < 2000   => 4,    // Moins de 2km
            $distanceInMeters < 5000   => 2,    // Moins de 5km
            $distanceInMeters < 10000  => 1,    // Moins de 10km
            $distanceInMeters < 20000  => 0.5,    // Moins de 20km
            default                    => 0     // Au-delà de 10km
        };

        $multiplier = match (true) {
            $responseTime < 1000  => 4,  // Moins de 10s
            $responseTime < 2000  => 2,  // Moins de 20s
            $responseTime < 3000  => 1,  // Moins de 30s
            default             => 0   // Trop lent
        };

        return $points * $multiplier;
    }


    public function giveAnswer(GameDTO $game, float $latitude, float $longitude): int
    {
        $currentPhoto = $this->getCurrentPhoto($game);
        if (!$currentPhoto) {
            throw new \Exception("Aucune photo actuelle trouvée.");
        }

        $serie = $this->serieService->getSerieById($game->serieId);
        $largeur = $serie->largeur;
        $distance = $this->calculateDistance(
            $latitude,
            $longitude,
            $currentPhoto->getLatitude(),
            $currentPhoto->getLongitude(),
            $largeur
        );

        $startTime = $game->startTime ?? new \DateTimeImmutable();
        $game->startTime = $startTime;

        $responseTime = time() - $startTime->getTimestamp();
        $score = $this->calculateScore($game, $distance, $responseTime);

        $game->score += $score;


        $this->gameRepository->save($game->toEntity());

        return $score;
    }

    public function getNextPhoto(GameDTO $game): ?Photo
    {

        // Vérifier si la partie est déjà terminée
        if ($this->isFinished($game)) {
            $this->endGame($game);
            return null;
        }

        // Passer à la photo suivante
        if ($game->currentPhotoIndex < count($game->photoIds) - 1) {
            $game->currentPhotoIndex++;

            // Sauvegarder en base
            $this->gameRepository->save($game->toEntity());

            // Retourner la nouvelle photo
            return $this->getCurrentPhoto($game);
        }

        // Si aucune photo suivante, marquer le jeu comme terminé
        $this->endGame($game);
        return null;
    }


    public function endGame(GameDTO $game): void
    {
        $game->state = 'FINISHED';
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

    public function getHighestScoreBySerieForUser(?string $serieId, string $userId): int|array
    {
        return $this->gameRepository->getHighestScoreBySerieForUser($serieId, $userId);
    }

    public function getGamesByUser(string $userId): array
    {
        //On transforme les entités en DTO
        $games = [];
        foreach ($this->gameRepository->getGamesByUser($userId) as $game) {
            $games[] = $game->toDTO();
        }

        return $games;
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
        $earthRadius = 6371000; // Rayon de la Terre en mètres

        // Conversion des degrés en radians
        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        // Différences de coordonnées
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        // Formule Haversine
        $angle = 2 * asin(
            sqrt(
                pow(sin($latDelta / 2), 2) +
                    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
            )
        );

        return $angle * $earthRadius; // Distance en mètres
    }
}
