<?php

namespace app_auth\infrastructure\repositories;

use app_auth\core\domain\entities\User;
use app_auth\core\repositoryInterfaces\AuthRepositoryInterface;
use app_auth\core\repositoryInterfaces\RepositoryDatabaseErrorException;
use app_auth\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use app_auth\core\services\geoquizz\ServiceGeoquizzInterface;
use Ramsey\Uuid\Uuid;

class PDOAuthRepository implements AuthRepositoryInterface
{
    private \PDO $pdoAuth;
    private ServiceGeoquizzInterface $sgi;

    public function __construct($pdo, $sgi)
    {
        $this->pdoAuth = $pdo;
        $this->sgi = $sgi;
    }

    public function save(User $user): string
    {
        $query = 'INSERT INTO user (id, password) VALUES (:id, :pwd)';
        try {
            $stmt = $this->pdoAuth->prepare($query);
            $stmt->bindValue(':id', $user->getID(), \PDO::PARAM_STR);
            $stmt->bindValue(':pwd', password_hash($user->password, PASSWORD_DEFAULT), \PDO::PARAM_STR);
            $stmt->execute();
        } catch (\PDOException $e) {
            throw new RepositoryDatabaseErrorException('Error while saving rdv $rdv ' . $e->getMessage());
        }
        return $user->getID();
    }

    public function getUserByEmail(string $email): User
    {
        $user = $this->sgi->getUserByEmail($email);
        $query = 'SELECT * FROM user WHERE id = :id';
        try {
            $stmt = $this->pdoAuth->prepare($query);
            $stmt->bindValue(':id', $user->id, \PDO::PARAM_STR);
            $stmt->execute();
            $userAuth = $stmt->fetch();
            if (!$userAuth) {
                throw new RepositoryEntityNotFoundException('User not found');
            }
        } catch (\PDOException $e) {
            $errorMessage = sprintf(
                "Erreur lors de la récupération de l'utilisateur (email: %s): %s \nTrace: %s",
                $email,
                $e->getMessage(),
                $e->getTraceAsString()
            );

            throw new RepositoryEntityNotFoundException($errorMessage, (int) $e->getCode(), $e);
        }
        $u = new User($user->email,$userAuth['password']);
        $u->setID($userAuth['id']);
        return $u;
    }

    public function getUserById(string $id): User
    {
        $user = $this->sgi->getUserById($id);
        $query = 'SELECT * FROM user WHERE id = :id';
        try {
            $stmt = $this->pdoAuth->prepare($query);
            $stmt->bindValue(':id', $id, \PDO::PARAM_STR);
            $stmt->execute();
            $userAuth = $stmt->fetch();
            if (!$userAuth) {
                throw new RepositoryEntityNotFoundException('User not found');
            }
        } catch (\PDOException $e) {
            throw new RepositoryDatabaseErrorException('Error while fetching user');
        }
        $user = new User($user->email, $userAuth['password']);
        $user->setID($userAuth['id']);
        return $user;
    }
    public function createUser(string $email, string $password): User
    {
        $user = new User($email, $password);
        $user->setID(Uuid::uuid4()->toString());
        $this->save($user);
        return $user;
    }
}
