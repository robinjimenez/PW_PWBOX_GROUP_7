<?php

namespace PWBox\Model;

interface FileRepository {

    public function add(File $file, float $fileSize);
    public function share(File $file,User $user);
    public function remove(File $file);
    public function getFilesFrom(Folder $folder);

}