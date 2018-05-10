<?php

namespace PWBox\Model\Implementation;

use Doctrine\DBAL\Connection;
use PWBox\Model\User;
use PWBox\Model\UserRepository;

class DoctrineUserRepository implements UserRepository
{
    private $database;

    public function __construct(Connection $database)
    {
        $this->database = $database;
    }

    public function save(User $user)
    {
        $sql = "INSERT INTO users(username, email, password, birthdate) VALUES(:username, :email, :password, :birthdate)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("username", $user->getUsername(), 'string');
        $stmt->bindValue("email", $user->getEmail(), 'string');
        $stmt->bindValue("password", $user->getPassword(), 'string');
        $stmt->bindValue("birthdate", $user->getBirthdate(), 'string');
        $stmt->execute();
    }

    public function login(User $user) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("email", $user->getEmail(), 'string');
        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result;//7)Retornem resultat de la query
    }
}