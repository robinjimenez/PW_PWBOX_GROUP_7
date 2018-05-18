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
        $sql = "INSERT INTO element(parent, name, owner, type) VALUES(:parent, :name, :owner, :type);";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("name", $folder->getName(), 'string');
        $stmt->bindValue("owner", $folder->getOwner(), 'string');
        $stmt->bindValue("parent", $folder->getParent(), 'bigint');
        $stmt->bindValue("type", $folder->getType(), 'string');
        $stmt->execute();

        // Obtenim l'identificador
        $id_child = $this->getIdByName($folder->getName(), $folder->getOwner());

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

        // Afegim la relacio usuari-element de admin
        $sql = "INSERT INTO user_element(user, element, role) VALUES(:user, :element, :role);";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("user", $folder->getOwner(), 'string');
        $stmt->bindValue("element", $id_child, 'bigint');
        $stmt->bindValue("role", "admin", 'string');
        $stmt->execute();

        //TODO: Afegir relacio usuari-element de reader (només per si estem afegint una carpeta a dins d'una carpeta que ja estava compartida)
        $sql = "select user from user_element
                where role = :role
                and element IN (select parent from closure
                                where child IN (select id from element where name = :subFolderName and owner = :owner)
                                and parent IN (select element from user_element where role = :role)
                                and parent != child)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("role", "reader", 'string');
        $stmt->bindValue("subFolderName", $folder->getName(), 'string');
        $stmt->bindValue("owner", $folder->getOwner(), 'string');
        $stmt->execute();
        $parentIsSharedWith = $stmt->fetchAll();

        if (count($parentIsSharedWith) > 0) {
            //Afegir a cada usuari a qui compartim la carpeta parent aquesta nova carpeta
            foreach ($parentIsSharedWith as $userArray) {
                $sql = "insert into user_element(user, element, role)
                        select :user, elements.id, :role
                        from element as elements
                        where elements.id IN (select id from element where name = :subFolderName and owner = :owner)";
                $stmt = $this->database->prepare($sql);
                $stmt->bindValue("user", $userArray['user'], 'string');
                $stmt->bindValue("role", "reader", 'string');
                $stmt->bindValue("subFolderName", $folder->getName(), 'string');
                $stmt->bindValue("owner", $folder->getOwner(), 'string');
                $stmt->execute();
            }
        }
    }

    public function remove(Folder $folder, User $user) {
        $sql = "DELETE FROM element WHERE name = :name AND owner := :user";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("name", $folder->getName(), 'string');
        $stmt->bindValue("user", $user->getUsername(), 'string');
        $stmt->execute();
    }

    public function rename(string $name, Folder $folder) {
        $sql = "UPDATE element SET name := :newName WHERE name = :name AND owner := :user";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("newName", $name, 'string');
        $stmt->bindValue("name", $folder->getName(), 'string');
        $stmt->bindValue("user", $_SESSION["userID"], 'string');
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

    public function getIdByName(string $name, string $owner)
    {
        $sql = "SELECT id FROM element WHERE name = :name AND owner = :owner";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("name", $name, 'string');
        $stmt->bindValue("owner", $owner, 'string');
        $stmt->execute();

        $result = $stmt->fetchColumn(0);

        return $result;
    }

    public function shareFolder(string $folderName, string $emailToShare, string $folderOwner) {
        //inserim nova relació a user_element
        $sql = "insert into user_element(user, element, role)
                select users.username, elements.id, :readerRole
                from user as users, element as elements
                where users.username IN (select username from user where email = :emailToShare)
                and elements.id IN (select id from element where name = :folderName and owner = :folderOwner)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("readerRole", 'reader', 'string');
        $stmt->bindValue("emailToShare", $emailToShare, 'string');
        $stmt->bindValue("folderName", $folderName, 'string');
        $stmt->bindValue("folderOwner", $folderOwner, 'string');
        $stmt->execute();

        //inserim nova relació a closure
        $sql = "insert into closure(parent, child, depth)
                select elementsP.id, elementsC.id, :depth
                from element as elementsP, element as elementsC
                where elementsP.id IN (select id from element where type = :sharedType and owner IN (select username from user where email = :emailToShare))
                and elementsC.id IN (select id from element where name = :folderName and owner = :folderOwner)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("depth", 1, 'integer');
        $stmt->bindValue("sharedType", 'shared', 'string');
        $stmt->bindValue("emailToShare", $emailToShare, 'string');
        $stmt->bindValue("folderName", $folderName, 'string');
        $stmt->bindValue("folderOwner", $folderOwner, 'string');
        $stmt->execute();

        //TODO: Compartir totes els childs d'aquesta carpeta que es vol compartir
        // Obtenir username a partir del email
        $sql = "select username from user where email = :email";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("email", $emailToShare, 'string');
        $stmt->execute();
        $usernameEmail = $stmt->fetchColumn(0);

        // Obtenir id de la carpeta shared de la persona del email
        $sql = "select id from element where owner = :owner and type = :shared";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("owner", $usernameEmail, 'string');
        $stmt->bindValue("shared", "shared", 'string');
        $stmt->execute();
        $idSharedFolder = $stmt->fetchColumn(0);

        // Seleccionar tots els childs de la carpeta a afegir que no estiguin ja compartits amb la persona
        $sql = "select * from closure as c
                where c.parent in (select e.id from element as e where e.name = :folderName and e.owner = :folderOwner)
                and c.parent != c.child
                and c.child NOT IN (select ue.element from user_element as ue
                                    where user = :usernameEmail and role = :reader)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("folderName", $folderName, 'string');
        $stmt->bindValue("folderOwner", $folderOwner, 'string');
        $stmt->bindValue("usernameEmail", $usernameEmail, 'string');
        $stmt->bindValue("reader", "reader", 'string');
        $stmt->execute();
        $childsToShare = $stmt->fetchAll();

        if (count($childsToShare) > 0) {
            // Afegir nova relació a closure per cada row retornada a la query anterior
            foreach ($childsToShare as $child) {
                $sql = "INSERT INTO closure(parent, child, depth)
                    VALUES (:newParentID, :childID, :newDepth)";
                $stmt = $this->database->prepare($sql);
                $stmt->bindValue("newParentID", $idSharedFolder, 'bigint');
                $stmt->bindValue("childID", $child['child'], 'bigint');
                $stmt->bindValue("newDepth", $child['depth'] + 1, 'integer');
                $stmt->execute();
            }

            // Afegir nova relació a user_element per cada row retornada a la query anterior
            foreach ($childsToShare as $child) {
                $sql = "insert into user_element(user, element,role)
                    values (:usernameEmail, :childID, :reader)";
                $stmt = $this->database->prepare($sql);
                $stmt->bindValue("usernameEmail", $usernameEmail, 'string');
                $stmt->bindValue("childID", $child['child'], 'bigint');
                $stmt->bindValue("reader", "reader", 'string');
                $stmt->execute();
            }
        }
    }
}