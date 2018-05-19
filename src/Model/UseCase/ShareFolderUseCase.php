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

    public function __invoke(string $folderName, string $emailToShare, string $folderOwner, string $folderPath)
    {
        //Afegir noves relacions a la bbdd
        $this->folderRepo->shareFolder($folderName, $emailToShare, $folderOwner);

        //TODO: Crear directori real de la carpeta

        //__DIR__.
        //SOURCE FILE
        $src = '/../../../public/uploads'. $folderPath . "/$folderName";
        //die(var_dump($src));

        //DESTINATION
        $dst = '/../../../public/uploads/anna/shared';

        //COPY
        //$this->xcopy($src, $dst);
    }

    //Funció per a copiar tot un directori i tot el que inclogui en un altre lloc
    /**
     * Copy a file, or recursively copy a folder and its contents
     * @author      Aidan Lister <aidan@php.net>
     * @version     1.0.1
     * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
     * @param       string   $source    Source path
     * @param       string   $dest      Destination path
     * @param       int      $permissions New folder creation permissions
     * @return      bool     Returns true on success, false on failure
     */
    function xcopy($source, $dest, $permissions = 0755)
    {
        // Check for symlinks
        if (is_link($source)) {
            return symlink(readlink($source), $dest);
        }

        // Simple copy for a file
        if (is_file($source)) {
            return copy($source, $dest);
        }

        // Make destination directory
        if (!is_dir($dest)) {
            mkdir($dest, $permissions);
        }

        // Loop through the folder
        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            // Deep copy directories
            xcopy("$source/$entry", "$dest/$entry", $permissions);
        }

        // Clean up
        $dir->close();
        return true;
    }
}