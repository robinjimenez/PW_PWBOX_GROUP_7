<?php
/**
 * Created by PhpStorm.
 * User: Jordi
 * Date: 13/5/18
 * Time: 23:47
 */

namespace PWBox\Model\UseCase;
use PWBox\Model\UserRepository;
use PWBox\Model\User;


class UpdateEmailUseCase
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
            "",
            $rawData['old-email'],
            "",
            ""
        );
        $newEmail = $rawData['new-data']['email'];
        $this->repo->updateEmail($user, $newEmail);
    }

}