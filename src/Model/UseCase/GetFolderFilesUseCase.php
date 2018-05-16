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

    public function __invoke(array $rawData)
    {

        $folder = new Folder(
            "",
            "",
            ""
        );

        $result = $this->repo->getFiles($folder,$owner); //No ens serveix la funció de login perquè retorna el user segons email, ara necessitem segons username
        return($result);
    }
}