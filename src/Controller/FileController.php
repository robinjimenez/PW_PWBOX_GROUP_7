<?php
namespace PWBox\Controller;

use Psr\Container\ContainerInterface;
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

                $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $fileName);
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