<?php

namespace PWBox\Model\UseCase;
use PWBox\Model\FolderRepository;
use PWBox\Model\UserRepository;
use PWBox\Model\Folder;

class DeleteFolderUseCase
{
    /** UserRepository */
    private $folderRepo;
    private $userRepo;

    public function __construct(FolderRepository $folderRepository, UserRepository $userRepository)
    {
        $this->folderRepo = $folderRepository;
        $this->userRepo = $userRepository;
    }

    public function __invoke(string $folderName, string $parent)
    {

        $folder = new Folder(
            $folderName,
            $_SESSION["userID"],
            $this->folderRepo->getIdByName($parent,$_SESSION['userID']),
            0
        );

        #Creem el fitxer a la bbdd
        $this->folderRepo->remove($folder);
    }
}