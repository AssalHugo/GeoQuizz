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
        try {
        $res = $this->entityManager->getRepository( User::class)->find($id);
        } catch(\Exception $e) {
            $errorMessage = sprintf(
                "Erreur lors de la récupération de l'utilisateur (ID: %s): %s \nTrace: %s",
                $id,
                $e->getMessage(),
                $e->getTraceAsString()
            );

            throw new UserServiceEntityNotFoundException($errorMessage, $e->getCode(), $e);
        }

        if($res == null) {
            throw new UserServiceEntityNotFoundException('User not found');
        }

        $user = new User();
        $user->setId($res->getId());
        $user->setEmail($res->getEmail());
        $user->setNickName($res->getNickName());
        return $user;
    }

    public function getUserByEmail(string $email): User
    {
        try {
            $res = $this->entityManager->getRepository(User::class)->findBy(['email' => $email]);
        } catch (\Exception $e) {
            $errorMessage = sprintf(
                "Erreur lors de la récupération de l'utilisateur (email: %s): %s \nTrace: %s",
                $email,
                $e->getMessage(),
                $e->getTraceAsString()
            );

            throw new UserServiceEntityNotFoundException($errorMessage, $e->getCode(), $e);
        }

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