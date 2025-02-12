<?php

namespace api_geoquizz\infrastructure\adaptaters;

use api_geoquizz\core\services\seriesDirectus\SerieDirectusInterface;
use GuzzleHttp\Client;
use api_geoquizz\core\dto\SerieDTO;
use api_geoquizz\core\domain\entities\seriesDirectus\Serie;
use api_geoquizz\core\domain\entities\seriesDirectus\Photo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class SerieDirectusServiceAdapter implements SerieDirectusInterface
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getSerieById($id): SerieDTO
    {
        try {
            $tokenDirectus = parse_ini_file('/var/php/../../tokenDirectus.ini')['TOKEN_DIRECTUS'];

            // Faire la requête à l'API Directus
            $response = $this->client->request('GET', '/items/series/' . $id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $tokenDirectus
                ],
            ]);
            $data = json_decode($response->getBody()->getContents(), true);
            $data = $data['data'];

            // Créer une nouvelle instance de Serie
            $serie = new Serie();

            // Setter les propriétés depuis la réponse
            $serie->setId($data['id'])
                ->setTitre($data['titre'])
                ->setLatitude((float)$data['latitude'])
                ->setLongitude((float)$data['longitude'])
                ->setLargeur((float)$data['largeur'])
                ->setPhotos($this->getPhotoBySerie($data['id']));

            // Convertir en DTO et retourner
            return $serie->toDTO();
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la récupération de la série: " . $e->getMessage());
        }
    }

    public function getPhotoBySerie($serieId): Collection
    {
        try {
            $tokenDirectus = parse_ini_file('/var/php/../../tokenDirectus.ini')['TOKEN_DIRECTUS'];

            // Faire la requête à l'API Directus
            $response = $this->client->request('GET', '/items/photos', [
                'query' => [
                    'filter[serie_id][_eq]' => $serieId
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $tokenDirectus
                ],
            ]);
            $data = json_decode($response->getBody()->getContents(), true)['data'];

            // Créer une nouvelle collection de Photo
            $photos = new ArrayCollection();

            // Setter les propriétés depuis la réponse
            foreach ($data as $photo) {
                $p = new Photo();
                $s = new Serie();
                $p->setId($photo['id'])
                    ->setPhoto($photo['photo'])
                    ->setLongitude((float)$photo['longitude'])
                    ->setLatitude((float)$photo['latitude'])
                    ->setAdresse($photo['adresse'])
                    ->setSerie($s->setId($photo['serie_id']));
                $photos->add($p);
            }

            return $photos;
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la récupération des photos: " . $e->getMessage());
        }
    }

    public function getAllSeries()
    {
        try {
            $tokenDirectus = parse_ini_file('/var/php/../../tokenDirectus.ini')['TOKEN_DIRECTUS'];
            
            // Faire la requête à l'API Directus
            $response = $this->client->request('GET', '/items/series',[
                'headers' => [
                    'Authorization' => 'Bearer ' . $tokenDirectus
                ]
            ]);
            $data = json_decode($response->getBody()->getContents(), true)['data'];

            // Créer une nouvelle collection de Serie
            $series = new ArrayCollection();

            // Setter les propriétés depuis la réponse
            foreach ($data as $serie) {
                $s = new Serie();
                $s->setId($serie['id'])
                    ->setTitre($serie['titre'])
                    ->setLatitude((float)$serie['latitude'])
                    ->setLongitude((float)$serie['longitude'])
                    ->setLargeur((float)$serie['largeur'])
                    ->setPhotos($this->getPhotoBySerie($serie['id']));
                $series->add($s);
            }

            return $series;
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la récupération des séries: " . $e->getMessage());
        }
    }
}
