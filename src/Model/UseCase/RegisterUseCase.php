<?php

namespace PWBox\Model\UseCase;

use PWBox\Model\UserRepository;
use PWBox\Model\User;


class RegisterUseCase {

    /** UserRepository */
    private $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(array $rawData)
    {
        $user = new User(
            $rawData['username'],
            $rawData['email'],
            $rawData['password'],
            $rawData['birthdate']
        );

        #Creem l'usuari a la BBDD i la seva carpeta
        $this->repo->save($user);
        mkdir(__DIR__. '/../../../public/uploads/'. $user->getUsername());
    }

}