<?php
namespace PWBox\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

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
        }

        //var_dump($args['params']);
        //$params = explode('/', $args['params']);

        if (isset($_POST["newFile"])) {
            $this->uploadFileAction($request,$response,$args['params']);
        }

        if (isset($_POST["newFolder"])) {
            $this->createFolderAction($args['params']);
        }

        return $this->container->get('view')
            ->render($response->withRedirect('/dashboard'. $args['params']), 'dash.twig', [
                'files' => null,
                'currentFolder' => $args['params'],
                'logged' => isset($_SESSION["userID"])]);
    }

    public function loadAction(Request $request, Response $response, array $args) {
        if (!isset($args['params'])) {
            return $response->withRedirect('/dashboard'. DIRECTORY_SEPARATOR . $_SESSION["userID"]);
        } else {
            //$this->container->get('get_folder_files_use_case');

            return $this->container->get('view')
                ->render($response,
                    'dash.twig', [
                        'files' => null,
                        'currentFolder' =>  $args['params'],
                        'logged' => isset($_SESSION["userID"])
                    ]);
        }
    }

    public function createFolderAction(string $curPath) {
        $path = $curPath . DIRECTORY_SEPARATOR . $_POST["folder"];
        mkdir(__DIR__ . "/../../public/uploads/". $path);
        //die(var_dump($_POST["folder"]));

        $path = explode('/', $curPath);

        //TODO: Registre folder a la bbdd (mes o menys implementat, falta solucionar que el parent a AddFolderUseCase sigui el id de la bbdd i no el name del parent
        $service = $this->container->get('add_folder_use_case');
        $service($path[sizeof($path)-1], $_POST["folder"]); //parent és curPath, name és $_POST["folder"]
    }

    /*public function showFormAction(Request $request, Response $response)
    {
        return $this->container->get('view')
            ->render($response, 'dash.twig', ['logged' => isset($_SESSION["userID"])]);
    }*/

    public function uploadFileAction(Request $request, Response $response, string $path) {

        $directory = __DIR__ . '/../../public/uploads/' . $path;

        $uploadedFiles = $request->getUploadedFiles();

        $errors = [];

        foreach ($uploadedFiles['files'] as $uploadedFile) {

            if (isset($uploadedFile) && $uploadedFile instanceof UploadedFile) {

                if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
                    $errors[] = sprintf(
                        'An unexpected error ocurred uploading the file %s',
                        $uploadedFile->getClientFilename()
                    );
                    continue;
                }

                if ($uploadedFile->getSize() > 2000000) {
                    $errors[] = sprintf(
                        'The file %s is too large! Max file size is 2Mb.',
                        $uploadedFile->getClientFilename()
                    );
                    continue;
                }

                $fileName = $uploadedFile->getClientFilename();

                $fileInfo = pathinfo($fileName);

                $extension = $fileInfo['extension'];

                if (!$this->isValidExtension($extension)) {
                    $errors[] = sprintf(
                        'Unable to upload the file %s, the extension %s is not valid',
                        $fileName,
                        $extension
                    );
                    continue;
                }

                //Guardar file a carpeta
                $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $fileName);

                //TODO: Registre file a la bbdd (implementat però no funciona):
                try {
                    $service = $this->container->get('add_file_use_case');
                    $service($uploadedFile->getClientFilename(), $uploadedFile->getSize(), $directory);
                } catch (\Exception $e) {
                    return var_dump($e);
                } catch (NotFoundExceptionInterface $e) {
                    return var_dump($e);
                } catch (ContainerExceptionInterface $e) {
                    return var_dump($e);
                }
            }
        }

        return $this->container->get('view')
            ->render($response,'dash.twig',['errors' => $errors, 'isPost' => true, 'logged' => isset($_SESSION["userID"])]);
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