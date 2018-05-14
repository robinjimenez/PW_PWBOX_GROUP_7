<?php

namespace PWBox\Model\UseCase;
use PWBox\Model\UserRepository;
use PWBox\Model\User;

class DeleteUserUseCase
{
    /** UserRepository */
    private $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(String $rawData)//Reb username com a rawData
    {
        $user = new User(
            $rawData,
            "",
            "",
            ""
        );
        $this->repo->deleteUser($user);
    }
}