<?php

namespace PWBox\Model\UseCase;

use PWBox\Model\UserRepository;
use PWBox\Model\FolderRepository;
use PWBox\Model\Folder;
use PWBox\Model\User;


class RegisterUseCase {

    /** UserRepository */
    private $userRepo;

    /** FolderRepository */
    private $folderRepo;

    public function __construct(UserRepository $userRepository,FolderRepository $folderRepository)
    {
        $this->userRepo = $userRepository;
        $this->folderRepo = $folderRepository;
    }

    public function __invoke(array $rawData)
    {
        $user = new User(
            $rawData['username'],
            $rawData['email'],
            $rawData['password'],
            $rawData['birthdate'],
            1000000000 //1 Gbyte
        );

        $root = new Folder(
            $user->getUsername(),
            $user->getUsername(),
            null
        );
        #Creem l'usuari a la BBDD i la seva carpeta
        $this->userRepo->save($user);
        $this->folderRepo->add($root);
        mkdir(__DIR__. '/../../../public/uploads/'. $user->getUsername());//potser es podria moure al controller igual que fem quan creem altres carpetes despres (o posar tot en els user case)
    }

}