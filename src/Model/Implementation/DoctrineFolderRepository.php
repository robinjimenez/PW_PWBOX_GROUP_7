<?php

namespace PWBox\Model\Implementation;

use Doctrine\DBAL\Connection;
use PWBox\Model\Folder;
use PWBox\Model\User;
use PWBox\Model\FolderRepository;

class DoctrineFolderRepository implements FolderRepository
{
    private $database;

    public function __construct(Connection $database)
    {
        $this->database = $database;
    }

    public function add(Folder $folder)
    {
        // Afegim l'element
        $sql = "INSERT INTO element(parent, name, owner, type) VALUES(:parent, :name, :owner, 'folder');";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("name", $folder->getName(), 'string');
        $stmt->bindValue("owner", $folder->getOwner(), 'string');
        $stmt->bindValue("parent", $folder->getParent(), 'bigint');
        $stmt->execute();

        // Obtenim l'identificador
        $id_child = $this->getIdByName($folder->getName());

        // Actualitzem l'arbre

        $sql = "INSERT INTO closure(parent, child, depth) VALUES (:this, :this, 0)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("this", $id_child, 'bigint');
        $stmt->execute();

        if ($folder->getParent() != null) {
            $sql = "INSERT INTO closure(parent, child, depth) SELECT p.parent, c.child, p.depth+c.depth+1 
                FROM closure p, closure c WHERE p.child = :parent AND c.parent = :child;";
            $stmt = $this->database->prepare($sql);
            $stmt->bindValue("parent", $folder->getParent(), 'bigint');
            $stmt->bindValue("child", $id_child, 'bigint');
            $stmt->execute();
        }

        // Afegim la relacio usuari-element
        $sql = "INSERT INTO user_element(user, element, role) VALUES(:user, :element, :role);";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("user", $folder->getOwner(), 'string');
        $stmt->bindValue("element", $id_child, 'bigint');
        $stmt->bindValue("role", "admin", 'string');
        $stmt->execute();
    }

    public function remove(Folder $folder) {
        $sql = "DELETE FROM element WHERE name = :name";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("name", $folder->getName(), 'string');
        $stmt->execute();
    }

    public function share(Folder $folder,User $user) {
        $sql = "SELECT * FROM user WHERE username = :username";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("username", $user->getUsername(), 'string');
        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result;
    }

    public function getFiles(string $folder, string $user) {
        $sql = "SELECT id FROM element e,user_element ue WHERE e.name = :name AND ue.element = e.id AND e.owner = :user";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("name", $folder, 'string');
        $stmt->bindValue("user", $user, 'string');
        $stmt->execute();

        $id = $stmt->fetchColumn(0);

        $sql = "SELECT name, type FROM element e,closure c WHERE c.parent = :id AND depth = 1 AND e.id = c.child AND e.owner = :user";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("id", $id, 'string');
        $stmt->bindValue("user", $user, 'string');
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