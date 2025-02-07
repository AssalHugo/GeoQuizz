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
        SerieDirectusInterface  $serieService
    )
    {
        $this->gameRepository = $gameRepository;
        $this->serieService = $serieService;
    }

    public function getGames(): array
    {
        $games = $this->gameRepository->findAll();
        return array_map(fn($games)=>$games->toDTO(), $games);
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
        return $game->currentPhotoIndex = count($game->photoIds);
    }

    public function startGame(GameDTO $game): void
    {
        $game->state = 'IN_PROGRESS';
        $game->startTime = (new \DateTimeImmutable());
        $this->gameRepository->save($game->toEntity());
    }

    public function calculateScore(GameDTO $game, float $distance, float $responseTime): int
    {
        $points = 0;
    
        // Gérer les petites distances en attribuant un score plus élevé
        if ($distance < 0.01) {  // Moins de 10 mètres
            $points = 10;
        } elseif ($distance < 0.05) {  // Moins de 50 mètres
            $points = 8;
        } elseif ($distance < 0.1) {  // Moins de 100 mètres
            $points = 6;
        } elseif ($distance < 0.2) {  // Moins de 200 mètres
            $points = 4;
        } else {
            $points = 2;
        }
    
        // Ajuster les points en fonction du temps de réponse
        $multiplier = 1;
        if ($responseTime < 5) {
            $multiplier = 4;
        } elseif ($responseTime < 10) {
            $multiplier = 2;
        } 
    var_dump($responseTime);
        // Calculer le score final
        $finalScore = $points * $multiplier;
    
        return $finalScore;
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
        // Vérifier si la partie est terminée
        if ($this->isFinished($game)) {
            throw new \Exception("Le jeu est terminé.");
        }

        // Passer à la photo suivante
        $game->currentPhotoIndex++;

        // Sauvegarder en base
        $this->gameRepository->save($game->toEntity());

        // Retourner la nouvelle photo
        return $this->getCurrentPhoto($game);
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
        float $lon2,
        float $largeur
    ): float {
        // Calculer la différence entre les latitudes et longitudes
        $latDiff = abs($lat2 - $lat1);
        $lonDiff = abs($lon2 - $lon1);
    
        // Afficher les différences de latitude et de longitude
        var_dump("Latitude Difference: ", $latDiff);
        var_dump("Longitude Difference: ", $lonDiff);
    
        // Calculer la distance approximative
        $distance = ($latDiff + $lonDiff) * 111;  // 1 degré de latitude ≈ 111 km
    
        // Ajuster la distance en fonction de la largeur
        $adjustedDistance = $distance * (1 + $largeur / 1000); // Ajuste la distance en fonction de la largeur
    
        // Affichage de la distance ajustée
        var_dump("Adjusted Distance: ", $adjustedDistance);
    
        return $adjustedDistance;
    }
    
        
    
    
    
}
