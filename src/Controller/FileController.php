<?php
namespace PWBox\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
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

/*        if (!isset($args['params'])) {
            //$args['params'] = DIRECTORY_SEPARATOR . $_SESSION["userID"];
            return $response->withRedirect('/dashboard'. DIRECTORY_SEPARATOR . $_SESSION["userID"]);
        }*/

        if (!isset($args['params'])) {
            $args['params'] = DIRECTORY_SEPARATOR . $_SESSION["userID"];
            return $response->withRedirect('/dashboard'. DIRECTORY_SEPARATOR . $_SESSION["userID"]);

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
                $this->shareFolderAction($request,$response,$args['params']);
            }

            if (isset($_POST["download"])) {
                $this->downloadFileAction($response, $args['params']);
            }

            if (isset($_POST["rename"])) {
                $this->renameFileAction($response,$args['params']);
            }

            if (isset($_POST["delete_file"])) {
                $result = $this->deleteFileAction($request,$response,$args['params']);
            }

            if (isset($_POST["delete_folder"])) {
                $result = $this->deleteFolderAction($request,$response,$args['params']);
            }

            /*return $this->container->get('view')
                ->render($response->withRedirect('/dashboard'. $args['params']), 'dash.twig', [
                    'files' => null,
                    'currentFolder' => $args['params'],
                    'logged' => isset($_SESSION["userID"])]);*/

            $params = explode('/', $args['params']);

            if (strcmp($_SESSION["userID"],$params[1]) != 0) {
                return $this->container->get('view')
                    ->render($response->withStatus(403), 'error.twig', []);
            }

            $service = $this->container->get('get_folder_files_use_case');
            $files = $service($args['params']);

            $up = $params;

            array_splice($up,sizeof($params)-1,1);

            $upPath = '/dashboard' . implode('/',$up);

            $user =$this->container->get('get_user_use_case')($_SESSION);

            $space = round($user['space']/1000000,2);

            if (isset($result['errors'])) {
                $errors = $result['errors'];
            }

            if (isset($result['path'])) {
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

    public function shareFolderAction(Request $request, Response $response, $args) {
        //Check email backend format
        $data = $request->getParsedBody();
        $result = [];

        //Format email
        if (v::email()->validate($data["email"]) == false) {
            $result['errors'][] = sprintf('Invalid email');

            //TODO: No mostra error
            //return $result;
            /*return $this->container->get('view')
                ->render($response,'dash.twig',['errors' => $errors, 'isPost' => true, 'logged' => isset($_SESSION["userID"])]);
      */} else {
            //Check if email exists in ddbb
            $service = $this->container->get('login_user_use_case');
            $queryResult = $service($data);//Obtinc el user si existeix

            if (isset($result[0])) {//user exists

                //Check it is not himself
                if ($queryResult[0]['username'] == $_SESSION['userID']) {
                    die("This is you. Can't share with yourself");
                    //TODO: No mostra error
                    $result['errors'][] = sprintf("You cannot share a folder with yourself");
                    //return $result;
                    /*return $this->container->get('view')
                        ->render($response,'dash.twig',['errors' => $errors, 'isPost' => true, 'logged' => isset($_SESSION["userID"])]);
*/
                }else {
                    //die("User exists. OK");
                    //Afegir la relació share a la bbdd
                    try {
                        $service = $this->container->get('share_folder_use_case');
                        //TODO: Obtenir nom real de la carpeta
                        $service($data['fileName'], $data['email'], $_SESSION['userID']);//Li passo el nom de la carpeta a compartir, el email amb qui compartir i el owner (userID sessió)

                    } catch (\Exception $e) {
                        die(var_dump($e));
                    }
                }

            } else {
                die("Not in ddbb");

                //TODO: No mostra error
                $result['errors'][] = sprintf('This user does not exists');
                //return $result;
                /*return $this->container->get('view')
                    ->render($response,'dash.twig',['errors' => $errors, 'isPost' => true, 'logged' => isset($_SESSION["userID"])]);
            */}
        }

        return $result;

        /*return $this->container->get('view')
            ->render($response->withRedirect('/dashboard'. $args['params']), 'dash.twig', [
                'files' => null,
                'currentFolder' => $args['params'],
                'logged' => isset($_SESSION["userID"])]);*/
    }

    /*public function loadAction(Request $request, Response $response, array $args) {
        if (!isset($args['params'])) {
            return $response->withRedirect('/dashboard'. DIRECTORY_SEPARATOR . $_SESSION["userID"]);
        } else {

            $params = explode('/', $args['params']);

            if (strcmp($_SESSION["userID"],$params[1]) != 0) {
                return $this->container->get('view')
                    ->render($response->withStatus(403), 'error.twig', []);
            }

            $service = $this->container->get('get_folder_files_use_case');
            $files = $service($args['params']);

            $up = $params;

            array_splice($up,sizeof($params)-1,1);

            $upPath = '/dashboard' . implode('/',$up);

            $user =$this->container->get('get_user_use_case')($_SESSION);

            $space = round($user['space']/1000000,2);

            return $this->container->get('view')
                ->render($response,
                    'dash.twig', [
                        'files' => $files,
                        'upPath' => $upPath,
                        'root' => $_SESSION["userID"],
                        'folder' => $params[sizeof($params)-1],
                        'path' =>  $args['params'],
                        'logged' => isset($_SESSION["userID"]),
                        'space' =>  $space

            ]);
        }
    }*/

    public function createFolderAction(string $curPath) {
        $path = $curPath . DIRECTORY_SEPARATOR . $_POST["folder"];
        mkdir(__DIR__ . "/../../public/uploads/". $path);

        $path = explode('/', $curPath);

        //Registre folder a la bbdd
        $service = $this->container->get('add_folder_use_case');
        $service($path[sizeof($path)-1], $_POST["folder"]); //parent és curPath, name és $_POST["folder"]
    }

    public function uploadFileAction(Request $request, Response $response, string $path) {

        $directory = __DIR__ . '/../../public/uploads/' . $path;

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

                $parent = $dir[sizeof($dir)-1];

                try {
                    $service = $this->container->get('add_file_use_case');
                    $service($uploadedFile->getClientFilename(), $uploadedFile->getSize(), $parent);
                    $result['posted'] = true;
                } catch (\Exception $e) {
                    return var_dump($e);
                } catch (NotFoundExceptionInterface $e) {
                    return var_dump($e);
                } catch (ContainerExceptionInterface $e) {
                    return var_dump($e);
                }
            }
        }

        return $result;
    }

    public function downloadFileAction(string $args) {

        $args = __DIR__ . '/../../public/uploads/' . $args;

        if (file_exists($args)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($args).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($args));
            readfile($args);
            exit;
        }
    }

    public function deleteFileAction(Request $request, Response $response, string $path) {

        $directory = __DIR__ . '/../../public/uploads/' . $path;

        $route = explode('/', $path);

        $this->container->get('delete_file_use_case')($route[sizeof($route)-1],0,$route[sizeof($route)-2]);

        array_splice($route,sizeof($route)-1,1);

        unlink($directory);

        $result['path'] = '/dashboard'. implode("/",$route);

        return $result;

    }

    public function deleteFolderAction(Request $request, Response $response, string $path) {

        $result = [];

        $files = $this->container->get('get_folder_files_use_case')($path);

        $directory = __DIR__ . '/../../public/uploads' . $path;

        $route = explode('/', $path);

        if (sizeof($files) == 0) {

            $this->container->get('delete_folder_use_case')($route[sizeof($route)-1],$route[sizeof($route)-2]);

            array_splice($route,sizeof($route)-1,1);

            rmdir($directory);

            $result['path'] = '/dashboard'. implode("/",$route);

            return $result;

        } else {

            array_splice($route,sizeof($route)-1,1);

            $result['path'] = '/dashboard'. implode("/",$route);

            $result['errors'][] = sprintf(
                "Can't delete folder, still has files in it!"
            );

            return $result;
        }
    }


    public function renameFileAction(Response $response, string $args) {

        if (file_exists($args)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($args).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($args));
            readfile($args);
            exit;
        }

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