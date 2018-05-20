<?php
namespace PWBox\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Exceptions\SubdivisionCode\EcSubdivisionCodeException;
use Respect\Validation\Validator as v;

use Slim\Http\UploadedFile;


class FileController {
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
        if (!isset($args['params'])) {
            $args['params'] = DIRECTORY_SEPARATOR . $_SESSION["userID"];
            return $response->withRedirect('/dashboard'. DIRECTORY_SEPARATOR . $_SESSION["userID"]);

        } else {

            $params = explode('/', $args['params']);

            if (strcmp($_SESSION["userID"],$params[1]) != 0) {
                return $this->container->get('view')
                    ->render($response->withStatus(403), 'error.twig', ['forbidden' => true]);
            }

            $up = $params;

            array_splice($up,sizeof($params)-1,1);

            $upPath = '/dashboard' . implode('/',$up);

            $user = $this->container->get('get_user_use_case')($_SESSION);

            $space = round($user['space']/1000000,2);

            $directory = __DIR__ . '/../../public/uploads' . implode('/',$params);


            if (!file_exists($directory) && !is_dir($directory) && sizeof($params) > 1) {
                return $response->withRedirect($upPath);
            } else {

                $result = [];
                $errors = [];

                if (isset($_POST["newFile"])) {
                    $result = $this->uploadFileAction($request,$response,$args['params']);
                }

                if (isset($_POST["newFolder"])) {
                    $result = $this->createFolderAction($args['params']);
                }

                if (isset($_POST["share"])) {
                    $result = $this->shareFolderAction($request,$response,$args['params']);
                }

                if (isset($_POST["download"])) {
                    $this->downloadFileAction($args['params']);
                }

                if (isset($_POST["rename"])) {
                    $this->renameFileAction($directory, $_POST['newName'],$_POST['fileName']);
                }

                if (isset($_POST["delete_file"])) {
                    $result = $this->deleteFileAction($request,$response,$args['params']);
                }

                if (isset($_POST["delete_folder"])) {
                    $result = $this->deleteFolderAction($request,$response,$args['params']);
                }

                if (isset($result['errors'])) {
                    $errors = $result['errors'];
                }

                $service = $this->container->get('get_folder_files_use_case');

                $files = $service($args['params']);

                if (isset($result['path'])) {
                    if (isset($result['errors'])) {
                        return $this->container->get('view')
                            ->render($response,
                                'error.twig', [
                                    'errors' => $errors,
                                    'backPath' => $result['path'],
                                    'forbidden' => false
                                ]);
                    } else {
                        return $this->container->get('view')
                            ->render($response->withRedirect($result['path']),
                                'dash.twig', [
                                    'errors' => $errors,
                                    'isPost' => isset($result['posted']),
                                    'files' => $files,
                                    'upPath' => $upPath,
                                    'root' => $_SESSION["userID"],
                                    'folder' => $params[sizeof($params)-1],
                                    'path' =>  $args['params'],
                                    'logged' => isset($_SESSION["userID"]),
                                    'space' =>  $space
                                ]);
                    }
                } else {
                    return $this->container->get('view')
                        ->render($response,
                            'dash.twig', [
                                'errors' => $errors,
                                'isPost' => isset($result['posted']),
                                'files' => $files,
                                'upPath' => $upPath,
                                'root' => $_SESSION["userID"],
                                'folder' => $params[sizeof($params)-1],
                                'path' =>  $args['params'],
                                'logged' => isset($_SESSION["userID"]),
                                'space' =>  $space
                            ]);
                }
            }
        }
    }

    public function shareFolderAction(Request $request, Response $response, $args) {
        //Check email backend format
        $data = $request->getParsedBody();
        $result = [];

        //Format email
        if (v::email()->validate($data["email"]) == false) {
            $result['errors'][] = sprintf('Invalid email');

        } else {
            //Check if email exists in ddbb
            $service = $this->container->get('login_user_use_case');
            $queryResult = $service($data); //Obtinc el user si existeix

            if (isset($queryResult[0]['username'])) {//user exists
                //Check it is not himself
                if ($queryResult[0]['username'] == $_SESSION['userID']) {
                    $result['errors'][] = sprintf("You cannot share a folder with yourself");

                }else {

                    //Afegir la relació share a la bbdd i copiar els directoris (tot en el service del model)
                    try {

                        $service = $this->container->get('share_folder_use_case');
                        //Li passo el nom de la carpeta a compartir, el email amb qui compartir i el owner (userID sessió). També args que conté el path fins la carpeta. queryResult es el username relacionat amb el email per fer share
                        $service($data['fileName'], $data['email'], $_SESSION['userID'], $args, $queryResult[0]['username']);

                    } catch (\Exception $e) {
                        $result['errors'][] = sprintf('Unable to share.');
                    }
                }
            } else {
                $result['errors'][] = sprintf('This user does not exist');
            }
        }

        return $result;
    }

    public function createFolderAction(string $curPath) {

        $route = explode('/', $curPath);

        $result = [];

        $role = $this->container->get('file_role_use_case')($route[sizeof($route)-1]);

        if ($role == "admin") {
            try {
                $path = $curPath . DIRECTORY_SEPARATOR . $_POST["folder"];
                if (!file_exists(__DIR__ . "/../../public/uploads/". $path)) {
                    mkdir(__DIR__ . "/../../public/uploads/". $path);
                }
                $path = explode('/', $curPath);

                $service = $this->container->get('add_folder_use_case');
                $service($path[sizeof($path)-1], $_POST["folder"]); //parent és curPath, name és $_POST["folder"]

            } catch (\Exception $e) {
                $result['errors'][] = sprintf("File already exists");
            }
        } else {
            $result['errors'][] = sprintf("Can't create folders from reader role.");
        }

        return $result;
    }

    public function uploadFileAction(Request $request, Response $response, string $path) {

        $directory = __DIR__ . '/../../public/uploads/' . $path;

        $route = explode('/', $path);

        $result = [];

        $role = $this->container->get('file_role_use_case')($route[sizeof($route)-1]);

        if ($role == "admin") {

            $uploadedFiles = $request->getUploadedFiles();

            $result['errors'] = [];

            foreach ($uploadedFiles['files'] as $uploadedFile) {

                if (isset($uploadedFile) && $uploadedFile instanceof UploadedFile) {

                    if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
                        $result['errors'][] = sprintf(
                            'An unexpected error ocurred uploading the file %s',
                            $uploadedFile->getClientFilename()
                        );
                        continue;
                    }

                    if ($uploadedFile->getSize() > 2000000) {
                        $result['errors'][] = sprintf(
                            'The file %s is too large! Max file size is 2Mb.',
                            $uploadedFile->getClientFilename()
                        );
                        continue;
                    }

                    $user = $this->container->get('get_user_use_case')($_SESSION);

                    if ($uploadedFile->getSize() > $user['space']) {
                        $result['errors'][] = sprintf(
                            "You don't have enough available space to store %s!",
                            $uploadedFile->getClientFilename()
                        );
                        continue;
                    }

                    $fileName = $uploadedFile->getClientFilename();

                    $fileInfo = pathinfo($fileName);

                    $extension = $fileInfo['extension'];

                    if (!$this->isValidExtension($extension)) {
                        $result['errors'][] = sprintf(
                            'Unable to upload the file %s, the extension %s is not valid',
                            $fileName,
                            $extension
                        );
                        continue;
                    }

                    //Guardar file a carpeta
                    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $fileName);

                    $dir = explode('/', $path);

                    $parent = $dir[sizeof($dir) - 1];

                    try {
                        $service = $this->container->get('add_file_use_case');
                        $service($uploadedFile->getClientFilename(), $uploadedFile->getSize(), $parent);
                        $result['posted'] = true;
                    } catch (\Exception $e) {
                        $result['errors'][] = sprintf(
                            'You have already uploaded this file: %s',
                            $uploadedFile->getClientFilename()
                        );
                    } catch (NotFoundExceptionInterface $e) {
                    } catch (ContainerExceptionInterface $e) {
                    }
                }
            }
        } else {
            $result['errors'] = sprintf("Can't upload files from reader role.");
        }

        return $result;
    }

    public function downloadFileAction(string $args) {

        $directory = __DIR__ . '/../../public/uploads' . $args;

        $route = explode('/', $args);

        $result = [];

        if (file_exists($directory)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($directory).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($directory));
            readfile($directory);
            exit;
        } else {
            $result['errors'][] = sprintf("Can't get file.");
        }

        array_splice($route,sizeof($route)-1,1);

        unlink($directory);

        $result['path'] = '/dashboard'. implode("/",$route);

        return $result;
    }

    public function deleteFileAction(Request $request, Response $response, string $path) {

        $directory = __DIR__ . '/../../public/uploads/' . $path;

        $route = explode('/', $path);

        $result = [];

        $role = $this->container->get('file_role_use_case')($route[sizeof($route)-1]);

        if ($role == "admin") {
            try {
                $this->container->get('delete_file_use_case')($route[sizeof($route)-1],0,$route[sizeof($route)-2]);

                array_splice($route,sizeof($route)-1,1);

                unlink($directory);

                $result['path'] = '/dashboard'. implode("/",$route);

            } catch (\Exception $e) {
                $result['errors'][] = sprintf("Couldn't delete file");
                return $result['errors'];
            }
        } else {

            array_splice($route,sizeof($route)-1,1);

            $result['path'] = '/dashboard'. implode("/",$route);

            $result['errors'][] = sprintf("You're not an admin, cannot delete from reader role.");
        }

        return $result;

    }

    public function deleteFolderAction(Request $request, Response $response, string $path) {

        $result = [];

        $files = $this->container->get('get_folder_files_use_case')($path);

        $directory = __DIR__ . '/../../public/uploads' . $path;

        $route = explode('/', $path);

        $role = $this->container->get('file_role_use_case')($route[sizeof($route)-1]);

        if ($role == "admin") {

            if (sizeof($files) == 0) {

                $this->container->get('delete_folder_use_case')($route[sizeof($route) - 1], $route[sizeof($route) - 2]);

                array_splice($route, sizeof($route) - 1, 1);

                rmdir($directory);

                $result['path'] = '/dashboard' . implode("/", $route);

                return $result;

            } else {

                array_splice($route, sizeof($route) - 1, 1);

                $result['path'] = '/dashboard' . implode("/", $route);

                $result['errors'][] = sprintf(
                    "Can't delete folder, still has files in it!"
                );

                return $result;
            }
        } else {
            array_splice($route, sizeof($route) - 1, 1);

            $result['path'] = '/dashboard' . implode("/", $route);

            $result['errors'][] = sprintf(
                "Can't delete folder, you're not an admin!"
            );

            return $result;
        }
    }


    public function renameFileAction(string $path, string $newName, string $fileName) {

        $result = [];

        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        if ($extension != "") {
            $newName = $newName . "." . $extension;
        }

        try {
            $this->container->get('rename_file_use_case')($newName,$fileName);

            rename($path . "/" .$fileName , $path . "/" .$newName);

        } catch (\Exception $e) {
            $result['errors'][] = sprintf("Couldn't rename file");
            return $result;
        }
        return $result;
    }

    /**
     * Validate the extension of the file
     *
     * @param string $extension
     * @return boolean
     */
    private function isValidExtension(string $extension)
    {
        $validExtensions = ['pdf','gif','jpg','png','md','txt'];

        return in_array($extension, $validExtensions);
    }
}