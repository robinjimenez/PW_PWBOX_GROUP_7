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
        $sql = "INSERT INTO element(parent, name, owner, type, size) VALUES(:parent, :name, :owner, 'file', :size)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("name", $file->getName(), 'string');
        $stmt->bindValue("owner", $file->getOwner(), 'string');
        $stmt->bindValue("parent", $file->getParent(), 'bigint');
        $stmt->bindValue("size", $file->getSize(), 'bigint');
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

        // Afegim la relacio usuari-element d'admin
        $sql = "INSERT INTO user_element(user, element, role) VALUES(:user, :element, :role);";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("user", $file->getOwner(), 'string');
        $stmt->bindValue("element", $id_child, 'bigint');
        $stmt->bindValue("role", "admin", 'string');
        $stmt->execute();

        // afegir la relació usuari-element com a reader en cas que el fitxer s'afegeixi dins de carpeta ja compartida.
        $sql = "select user from user_element
                where role = :role
                and element IN (select parent from closure
                                where child IN (select id from element where name = :subFileName and owner = :owner)
                                and parent IN (select element from user_element where role = :role)
                                and parent != child)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("role", "reader", 'string');
        $stmt->bindValue("subFileName", $file->getName(), 'string');
        $stmt->bindValue("owner", $file->getOwner(), 'string');
        $stmt->execute();
        $parentIsSharedWith = $stmt->fetchAll();
        if (count($parentIsSharedWith) > 0) {
            //Afegir a cada usuari a qui compartim la carpeta parent aquest nou fitxer
            foreach ($parentIsSharedWith as $userArray) {
                $sql = "insert into user_element(user, element, role)
                        select :user, elements.id, :role
                        from element as elements
                        where elements.id IN (select id from element where name = :subFileName and owner = :owner)";
                $stmt = $this->database->prepare($sql);
                $stmt->bindValue("user", $userArray['user'], 'string');
                $stmt->bindValue("role", "reader", 'string');
                $stmt->bindValue("subFileName", $file->getName(), 'string');
                $stmt->bindValue("owner", $file->getOwner(), 'string');
                $stmt->execute();
            }
        }

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

        $id = $this->getIdByName($file->getName());

        $sql = "delete link
                from closure p, closure link, closure c, closure to_delete
                where p.parent = link.parent and c.child = link.child
                and p.child  = to_delete.parent and c.parent= to_delete.child
                and (to_delete.parent= :id or to_delete.child= :id)
                and to_delete.depth<2";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("id", $id, 'string');
        $stmt->execute();

        $sql = "select size from element where id = :id and owner = :owner";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("id", $id, 'string');
        $stmt->bindValue("owner", $_SESSION['userID'], 'string');
        $stmt->execute();

        $space = $stmt->fetchColumn(0);

        $sql = "update user set space := space + :newSpace  where username = :username";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("username", $_SESSION['userID'], 'string');
        $stmt->bindValue("newSpace", $space, 'string');
        $stmt->execute();

        $sql = "delete
                from user_element
                where element = :id";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("id", $id, 'string');
        $stmt->execute();

        $sql = "delete from element where id = :id";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("id", $id, 'string');
        $stmt->execute();

    }

    public function rename(string $name, File $file) {
        $sql = "UPDATE element SET name := :newName WHERE name = :name AND owner := :user";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("newName", $name, 'string');
        $stmt->bindValue("name", $file->getName(), 'string');
        $stmt->bindValue("user", $_SESSION["userID"], 'string');
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