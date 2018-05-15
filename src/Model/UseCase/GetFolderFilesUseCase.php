<?php

namespace PWBox\Model\UseCase;
use PWBox\Model\FolderRepository;
use PWBox\Model\User;


class GetFolderFilesUseCase
{
    /** UserRepository */
    private $repo;

    public function __construct(FolderRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(array $rawData)
    {
        $username = $rawData['userID'];

        $user = new User(
            $username,
            "",
            "",
            ""
        );

        $result = $this->repo->getUser($user);//No ens serveix la funció de login perquè retorna el user segons email, ara necessitem segons username
        return($result);
    }
}