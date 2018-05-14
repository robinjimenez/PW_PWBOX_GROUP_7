<?php
/**
 * Created by PhpStorm.
 * User: Jordi
 * Date: 13/5/18
 * Time: 22:51
 */

namespace PWBox\Model\UseCase;
use PWBox\Model\UserRepository;
use PWBox\Model\User;


class GetUserUseCase
{
    /** UserRepository */
    private $repo;

    public function __construct(UserRepository $repo)
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