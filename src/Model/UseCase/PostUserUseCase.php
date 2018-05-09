<?php

namespace PWBox\Model\UseCase;

use PWBox\Model\UserRepository;
use PWBox\Model\User;


class PostUserUseCase {

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
        $this->repo->save($user);
    }

}