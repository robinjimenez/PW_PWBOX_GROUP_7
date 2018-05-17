<?php

namespace PWBox\Model;

interface FileRepository {

    public function add(File $file);
    public function share(File $file,User $user);
    public function remove(File $file);
    public function rename(string $name, File $file);
    public function getFilesFrom(Folder $folder);
    public function getIdByName(string $name);

}