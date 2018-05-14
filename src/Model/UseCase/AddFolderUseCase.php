<?php

namespace PWBox\Model\UseCase;

use PWBox\Model\Folder;
use PWBox\Model\FolderRepository;
use PWBox\Model\UserRepository;
use PWBox\Model\User;


class AddFolderUseCase {

    /** UserRepository */
    private $repo;

    public function __construct(FolderRepository $folderRepository)
    {
        $this->repo = $folderRepository;
    }

    public function __invoke(array $rawData)
    {
        $folder = new Folder(
            $rawData['parent'],
            $rawData['name'],
            $rawData['owner']
        );

        #Creem l'usuari a la BBDD i la seva carpeta
        $this->folderRepo->add($folder);
        mkdir(__DIR__. '/../../../public/uploads/'. $folder->getParent());
    }

}