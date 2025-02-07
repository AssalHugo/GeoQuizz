<?php

use PHPUnit\Framework\TestCase;
use api_geoquizz\core\services\GameService;
use api_geoquizz\infrastructure\repositories\GameRepository;
use api_geoquizz\infrastructure\adaptaters\SerieDirectusServiceAdapter;
use api_geoquizz\core\domain\entities\geoquizz\Game;
use api_geoquizz\core\dto\GameDTO;
use api_geoquizz\core\domain\entities\seriesDirectus\Serie;
use api_geoquizz\core\domain\entities\seriesDirectus\Photo;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Doctrine\Common\Collections\ArrayCollection;

class GameServiceTest extends TestCase
{
    private GameService $gameService;
    private $entityManagerMock;
    private $gameRepository;
    private $serieServiceMock;
    private $httpClientMock;

    protected function setUp(): void
    {
        // Mock de l'EntityManager pour éviter une vraie connexion à la BDD
        $this->entityManagerMock = $this->createMock(EntityManager::class);
        
        // Mock du repository en lui passant l'EntityManager factice
        $this->gameRepository = new GameRepository($this->entityManagerMock);

        // Mock du client HTTP pour éviter les appels API
        $this->httpClientMock = $this->createMock(Client::class);
        $this->serieServiceMock = new SerieDirectusServiceAdapter($this->httpClientMock);

        // Création du service avec les mocks
        $this->gameService = new GameService($this->gameRepository, $this->serieServiceMock);
    }

    public function testCreateGame()
    {
        $serieId = 1;
        $userId = 'user_456';
    
        // Simule une réponse JSON de l'API Directus
        $responseBody = json_encode([
            'data' => [
                'id' => $serieId,
                'titre' => 'Paris Tour',
                'latitude' => 48.8566,
                'longitude' => 2.3522,
                'largeur' => 10,
            ]
        ]);
    
        $this->httpClientMock->method('request')->willReturn(new Response(200, [], $responseBody));
    
        // Fake photos (assure-toi que l'ID est bien un int)
        $photo1 = new Photo();
        $photo1->setId(1)
               ->setPhoto('photo1.jpg')
               ->setLatitude(48.8566)
               ->setLongitude(2.3522)
               ->setAdresse('Adresse 1');
    
        $photo2 = new Photo();
        $photo2->setId(2)
               ->setPhoto('photo2.jpg')
               ->setLatitude(48.8570)
               ->setLongitude(2.3530)
               ->setAdresse('Adresse 2');
    
        $photos = new ArrayCollection([$photo1, $photo2]);
    
        // Fake Serie avec toutes ses propriétés bien définies
        $serie = new Serie();
        $serie->setId($serieId)
              ->setTitre('Paris Tour')
              ->setLatitude(48.8566)
              ->setLongitude(2.3522)
              ->setLargeur(10)
              ->setPhotos($photos);
    
        // On injecte la série simulée
        $this->serieServiceMock = $this->createMock(SerieDirectusServiceAdapter::class);
        $this->serieServiceMock->method('getSerieById')->willReturn($serie->toDTO());
    
        // Instanciation du service avec le faux service Directus
        $this->gameService = new GameService($this->gameRepository, $this->serieServiceMock);
    
        // Mock pour éviter un accès réel à la BDD
        $this->entityManagerMock->expects($this->once())->method('persist');
        $this->entityManagerMock->expects($this->once())->method('flush');
    
        $gameDTO = $this->gameService->createGame($serieId, $userId);
    
        $this->assertInstanceOf(GameDTO::class, $gameDTO);
        $this->assertEquals($userId, $gameDTO->userId);
        $this->assertEquals($serieId, $gameDTO->serieId);
    }

    public function testCalculateScore()
    {

    }
}
