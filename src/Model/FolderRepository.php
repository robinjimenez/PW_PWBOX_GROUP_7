<?php

namespace PWBox\Model;

interface FolderRepository {

    public function add(Folder $folder);
    public function share(Folder $folder,User $user);
    public function remove(Folder $folder, User $user);
    public function rename(string $name, Folder $folder);
    public function getIdByName(string $name);
    public function getFiles(string $folder, string $user);

}