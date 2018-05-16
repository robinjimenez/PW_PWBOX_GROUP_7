<?php

namespace PWBox\Model\Implementation;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use PWBox\Model\File;
use PWBox\Model\Folder;
use PWBox\Model\User;
use PWBox\Model\FileRepository;

class DoctrineFileRepository implements FileRepository
{
    private $database;

    public function __construct(Connection $database)
    {
        $this->database = $database;
    }

    public function add(File $file)
    {

        // Afegim l'element
        $sql = "INSERT INTO element(parent, name, owner, type) VALUES(:parent, :name, :owner, 'file')";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("name", $file->getName(), 'string');
        $stmt->bindValue("owner", $file->getOwner(), 'string');
        $stmt->bindValue("parent", $file->getParent(), 'bigint');
        try {
            $stmt->execute();
        } catch (DBALException $e) {
            return var_dump($e);
        }

        // Obtenim l'identificador
        $id_child = $this->getIdByName($file->getName());

        $sql = "INSERT INTO closure(parent, child, depth) VALUES (:this, :this, 0)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("this", $id_child, 'bigint');
        $stmt->execute();

        // Actualitzem l'arbre
            $sql = "INSERT INTO closure(parent, child, depth) SELECT p.parent, c.child, p.depth+c.depth+1
                FROM closure AS p, closure AS c WHERE p.child = :parent AND c.parent = :child;";
            $stmt = $this->database->prepare($sql);
            $stmt->bindValue("parent", $file->getParent(), 'bigint');
            $stmt->bindValue("child", $id_child, 'bigint');
            $stmt->execute();

        // Afegim la relacio usuari-element
        $sql = "INSERT INTO user_element(user, element, role) VALUES(:user, :element, :role);";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("user", $file->getOwner(), 'string');
        $stmt->bindValue("element", $id_child, 'bigint');
        $stmt->bindValue("role", "admin", 'string');
        $stmt->execute();

        //Restar espai de l'usuari
        $sql = "SELECT space FROM user WHERE username = :username";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("username", $_SESSION["userID"], 'string');
        $stmt->execute();

        $userSpace = $stmt->fetchColumn(0);

        $newSpace = $userSpace - $file->getSize();

        $sql = "UPDATE user SET space = space - :newSpace WHERE username = :username";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("newSpace", $file->getSize(), 'bigint');
        $stmt->bindValue("username", $_SESSION["userID"], 'string');
        $stmt->execute();
    }

    public function remove(File $file) {
        $sql = "DELETE FROM element WHERE name = :name";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("name", $file->getName(), 'string');
        $stmt->execute();

        $sql = "UPDATE user SET space = space + :newSpace WHERE username = :username";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("newSpace", $file->getSize(), 'bigint');
        $stmt->bindValue("username", $_SESSION["userID"], 'string');
        $stmt->execute();
    }

    public function share(File $file,User $user) {
        $sql = "SELECT * FROM user WHERE username = :username";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("username", $user->getUsername(), 'string');
        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result;
    }

    public function getFilesFrom(Folder $folder) {
        $sql = "SELECT * FROM element WHERE name = :name";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("name", $folder->getName(), 'string');
        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result;
    }

    public function getIdByName(string $name)
    {
        $sql = "SELECT id FROM element WHERE name = :name";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("name", $name, 'string');
        $stmt->execute();

        $result = $stmt->fetchColumn(0);

        return $result;
    }

}