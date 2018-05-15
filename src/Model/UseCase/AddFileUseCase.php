<?php

namespace PWBox\Model\UseCase;
use PWBox\Model\FileRepository;
use PWBox\Model\UserRepository;
use PWBox\Model\File;

class AddFileUseCase
{
    /** UserRepository */
    private $fileRepo;
    private $userRepo;

    public function __construct(FileRepository $fileRepository, UserRepository $userRepository)
    {
        $this->fileRepo = $fileRepository;
        $this->userRepo = $userRepository;
    }

    public function __invoke(string $fileName, float $fileSize, string $directory)
    {

        $file = new File(
            $directory,
            $fileName,
            $_SESSION["userID"]
        );

        #Creem el fitxer a la bbdd
        $this->fileRepo->add($file, $fileSize);
        //mkdir(__DIR__. '/../../../public/uploads/'. $folder->getParent());
    }
}