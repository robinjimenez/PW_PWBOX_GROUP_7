<?php

namespace PWBox\Model\Implementation;

use Doctrine\DBAL\Connection;
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
        $sql = "INSERT INTO element(name, owner, parent) VALUES(:name, :owner, :parent)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("name", $file->getName(), 'string');
        $stmt->bindValue("owner", $file->getOwner(), 'string');
        $stmt->bindValue("parent", $file->getParent(), 'bigint');
        $stmt->execute();
    }

    public function remove(File $file) {
        $sql = "DELETE FROM element WHERE name = :name";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue("name", $file->getName(), 'string');
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

}