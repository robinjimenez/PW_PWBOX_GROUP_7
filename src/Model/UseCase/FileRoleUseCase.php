<?php

namespace PWBox\Model\UseCase;
use PWBox\Model\FileRepository;
use PWBox\Model\File;

class FileRoleUseCase
{
    /** UserRepository */
    private $fileRepo;

    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepo = $fileRepository;
    }

    public function __invoke(string $fileName)
    {

        $file = new File(
            $fileName,
            $_SESSION["userID"],
            0,
            0
        );

        return $this->fileRepo->getRoleByName($file);
    }
}