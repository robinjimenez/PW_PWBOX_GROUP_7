<?php

namespace PWBox\Model;

interface FolderRepository {

    public function add(Folder $folder);
    public function share(Folder $folder,User $user);
    public function remove(Folder $folder);
    public function rename(string $name, Folder $folder);
    public function getIdByName(string $name, string $owner);
    public function getFiles(string $folder, string $user);
    public function shareFolder(string $folderName, string $emailToShare, string $folderOwner);

}