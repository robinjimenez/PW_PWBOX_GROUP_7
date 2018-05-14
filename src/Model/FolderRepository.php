<?php

namespace PWBox\Model;

interface FolderRepository {

    public function add(Folder $folder);
    public function share(Folder $folder,User $user);
    public function remove(Folder $folder);
    public function getFiles(Folder $folder);

}