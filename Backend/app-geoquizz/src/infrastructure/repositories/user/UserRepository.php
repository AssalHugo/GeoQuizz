<?php

namespace api_geoquizz\infrastructure\repositories\user;

use api_geoquizz\core\domain\entities\geoquizz\User;
use api_geoquizz\core\repositoryInterface\user\UserRepositoryInterface;
use api_geoquizz\core\services\user\UserServiceEntityNotFoundException;
use Doctrine\ORM\EntityManager;

class UserRepository implements UserRepositoryInterface {
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(User $user): string
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user->getId();
    }

    public function createUser(string $id, string $email, string $nickname): User
    {
        $user = new User();
        $user->setId($id);
        $user->setEmail($email);
        $user->setNickName($nickname);
        $this->save($user);
        return $user;
    }

    public function getUserById(string $id): User
    {
        $res = $this->entityManager->getRepository(User::class)->findBy(['id' => $id]);

        if($res == null) {
            throw new UserServiceEntityNotFoundException('User not found');
        }

        $user = new User();
        $user->setId($res[0]->getId());
        $user->setEmail($res[0]->getEmail());
        $user->setNickName($res[0]->getNickName());
        return $user;
    }

    public function getUserByEmail(string $email): User
    {
        $res = $this->entityManager->getRepository(User::class)->findBy(['email' => $email]);

        if($res == null) {
            throw new UserServiceEntityNotFoundException('User not found');
        }

        $user = new User();
        $user->setId($res[0]->getId());
        $user->setEmail($res[0]->getEmail());
        $user->setNickName($res[0]->getNickName());
        return $user;
    }
}