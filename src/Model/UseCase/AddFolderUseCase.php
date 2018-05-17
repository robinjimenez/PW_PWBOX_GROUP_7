<?php

namespace PWBox\Model\UseCase;

use PWBox\Model\Folder;
use PWBox\Model\FolderRepository;
use PWBox\Model\UserRepository;
use PWBox\Model\User;


class AddFolderUseCase {

    /** FolderRepository */
    private $folderRepo;

    public function __construct(FolderRepository $folderRepository)
    {
        $this->folderRepo = $folderRepository;
    }

    public function __invoke(string $parent, string $name)
    {

        $folder = new Folder(
            $name,
            $_SESSION["userID"],
            $this->folderRepo->getIdByName($parent, $_SESSION["userID"]),
            "folder"
        );

        #Creem la carpeta a la bbdd
        $this->folderRepo->add($folder);
        //mkdir(__DIR__. '/../../../public/uploads/'. $folder->getParent());
    }

}