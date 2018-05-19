<?php

namespace PWBox\Model\UseCase;

use PWBox\Model\FolderRepository;

class ShareFolderUseCase
{
    /** FolderRepository */
    private $folderRepo;

    public function __construct(FolderRepository $folderRepository)
    {
        $this->folderRepo = $folderRepository;
    }

    public function __invoke(string $folderName, string $emailToShare, string $folderOwner)
    {
        //Afegir noves relacions a la bbdd
        $this->folderRepo->shareFolder($folderName, $emailToShare, $folderOwner);

        //TODO: Crear directori real de la carpeta

        //SOURCE FILE
        //$src = __DIR__. "/../../../public/uploads/$_SESSION[userID]". ;
        //$src = $folderName->getParent();



        //DESTINATION
        //$dst = __DIR__. "/../../../public/uploads/$_SESSION[userID]". ;
        //die(var_dump($src));


        //COPY
        //$this->recurse_copy($src, $dst);
    }

    //Funció per a copiar tot un directori i tot el que inclogui en un altre lloc
    function recurse_copy($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}