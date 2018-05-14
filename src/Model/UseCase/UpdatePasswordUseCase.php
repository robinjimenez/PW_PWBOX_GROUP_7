<?php

namespace PWBox\Model\UseCase;
use PWBox\Model\UserRepository;
use PWBox\Model\User;

class UpdatePasswordUseCase
{
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
            "",
            $rawData['new-password'],
            ""
        );

        $this->repo->updatePassword($user);
    }
}