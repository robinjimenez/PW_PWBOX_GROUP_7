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
        //$parent_length = strlen($parent);
        //$parentOK = substr($parent, 1, $parent_length); //Trec el "/" del parent

        //TODO: Parent ha de ser el ID de la bbdd del parent, no el seu nom

        $folder = new Folder(
            $name,
            $_SESSION["userID"],
            $this->folderRepo->getIdByName($parent)
        );

        #Creem la carpeta a la bbdd
        $this->folderRepo->add($folder);
        //mkdir(__DIR__. '/../../../public/uploads/'. $folder->getParent());
    }

}