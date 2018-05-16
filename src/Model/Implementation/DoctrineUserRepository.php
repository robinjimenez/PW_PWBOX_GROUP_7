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
        $sql = "INSERT INTO user(username, email, password, birthdate, space) VALUES(:username, :email, :password, :birthdate, :space)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("username", $user->getUsername(), 'string');
        $stmt->bindValue("email", $user->getEmail(), 'string');
        $stmt->bindValue("password", $user->getPassword(), 'string');
        $stmt->bindValue("birthdate", $user->getBirthdate(), 'string');
        $stmt->bindValue("space", $user->getSpace(), 'float');
        $stmt->execute();
    }

    public function login(User $user) {//Retorna l'usuari a partir del email
        $sql = "SELECT * FROM user WHERE email = :email";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("email", $user->getEmail(), 'string');
        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result;//7)Retornem resultat de la query
    }

    public function getUser(User $user) {//Retorna l'usuari a partir del username
        $sql = "SELECT * FROM user WHERE username = :username";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("username", $user->getUsername(), 'string');
        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result;
    }

    public function updateEmail(User $user, String $newEmail) {
        $sql = "UPDATE user SET email = :newEmail WHERE email LIKE :oldEmail";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("newEmail", $newEmail, 'string');
        $stmt->bindValue("oldEmail", $user->getEmail(), 'string');
        $stmt->execute();
    }

    public function updatePassword(User $user) {
        $sql = "UPDATE user SET password = :newPassword WHERE username LIKE :username";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("newPassword", $user->getPassword(), 'string');
        $stmt->bindValue("username", $user->getUsername(), 'string');
        $stmt->execute();
    }

    public function deleteUser(User $user) {

        $sql = "delete
                from closure
                where child IN (select ue.element from user_element as ue
                                where ue.user = :username)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("username", $user->getUsername(), 'string');
        $stmt->execute();

        $sql = "delete
                from user_element
                where user = :username";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("username", $user->getUsername(), 'string');
        $stmt->execute();

        $sql = "ALTER TABLE element DROP FOREIGN KEY parentFK";
        $stmt = $this->database->prepare($sql);
        $stmt->execute();

        $sql = "delete
                from element where owner = :username";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("username", $user->getUsername(), 'string');
        $stmt->execute();

        $sql = "ALTER TABLE element
                ADD FOREIGN KEY (parent) REFERENCES element(id)";
        $stmt = $this->database->prepare($sql);
        $stmt->execute();

        $sql = "DELETE FROM user WHERE username = :username";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("username", $user->getUsername(), 'string');
        $stmt->execute();
    }
}