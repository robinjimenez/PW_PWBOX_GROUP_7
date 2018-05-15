<?php

namespace PWBox\Model\UseCase;
use PWBox\Model\UserRepository;
use PWBox\Model\User;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

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
            "",
            ""
        );
        $this->repo->deleteUser($user);

        //Remove user folder and content
        $dir = __DIR__. '/../../../public/uploads/'. $user->getUsername();
        $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it,
            RecursiveIteratorIterator::CHILD_FIRST);
        foreach($files as $file) {
            if ($file->isDir()){
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($dir);
    }
}