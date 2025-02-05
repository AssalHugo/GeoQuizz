<?php


namespace api_geoquizz\infrastructure\repositories;

use api_geoquizz\core\domain\entities\geoquizz\User;
use api_geoquizz\core\repositoryInterface\UserRepositoryInterface;
use Doctrine\ORM\EntityManager;

class UserRepository implements UserRepositoryInterface {
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function save(User $user): void {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function findById(string $id): ?User {
        return $this->entityManager->find(User::class, $id);
    }
    
}