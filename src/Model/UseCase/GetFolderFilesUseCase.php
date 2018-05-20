<?php

namespace PWBox\Model\UseCase;

use PWBox\Model\FolderRepository;
use PWBox\Model\Folder;


class GetFolderFilesUseCase
{
    /** FolderRepository */
    private $repo;

    public function __construct(FolderRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(string $path)
    {
        $dir = explode('/', $path);

        $folder = $dir[sizeof($dir)-1];

        $result = $this->repo->getFiles($folder,$_SESSION["userID"]);

        return($result);
    }
}