<?php

namespace PWBox\Model\UseCase;
use PWBox\Model\FileRepository;
use PWBox\Model\UserRepository;
use PWBox\Model\File;

class RenameFileUseCase
{
    /** UserRepository */
    private $fileRepo;
    private $userRepo;

    public function __construct(FileRepository $fileRepository, UserRepository $userRepository)
    {
        $this->fileRepo = $fileRepository;
        $this->userRepo = $userRepository;
    }

    public function __invoke(string $newName, string $fileName)
    {

        $file = new File(
            $fileName,
            $_SESSION["userID"],
            0,
            0
        );

        $this->fileRepo->rename($newName, $file);
    }
}