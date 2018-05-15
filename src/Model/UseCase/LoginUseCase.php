<?php

namespace PWBox\Model\UseCase;
use PWBox\Model\UserRepository;
use PWBox\Model\User;


class LoginUseCase
{
    /** UserRepository */
    private $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(array $rawData)//4) Al crear una instància es crida a aquest mètode automàticament
    {
        $user = new User(//5)Creem una nova instància de user amb les dades rebudes del formulari
            "",
            $rawData['email'],
            $rawData['password'],
            "",
            ""
        );
        $result = $this->repo->login($user);//6)Es crida a la funció de login i li passem la instància de user

        return($result);//8)Retornem resultat de la query
    }

}