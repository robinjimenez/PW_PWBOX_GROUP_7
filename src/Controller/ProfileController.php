<?php

namespace PWBox\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

class ProfileController {
    protected $container;
    protected $username;
    protected $birthdate;
    protected $email;
    protected $password;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    //Mètode per la crida GET de profile
    public function __invoke(Request $request, Response $response, array $args)
    {
        $this->getProfileInfo();
        return $this->container->get('view')
            ->render($response, 'profile.twig', ['error' => false, 'username' => $this->username, 'birthdate' => $this->birthdate, 'email' => $this->email, 'password' => $this->password, 'logged' => isset($_SESSION["userID"])]);
    }

    public function getProfileInfo() {
        //TODO: Obtenir de BBDD dades de l'usuari de la sessió actual ($_SESSION["userID"] em dóna el seu username)
        $data = $_SESSION;
        $service = $this->container->get('get_user_use_case');
        $ddbbServiceResult = $service($data);//Li passo l'array de session, que conten el mail de l'usuari de la sessió com a ID
        //return var_dump($ddbbServiceResult);

        $this->username = $ddbbServiceResult[0]['username'];
        $this->birthdate = $ddbbServiceResult[0]['birthdate'];
        $this->email = $ddbbServiceResult[0]['email'];//Igual a $_SESSION["userID"]
        $this->password = $ddbbServiceResult[0]['password'];
    }

    //Mètode per la crida POST de profile
    public function updateProfileAction(Request $request, Response $response) {
        $data = $request->getParsedBody();

        if (isset($data['email-button'])) {
            //Clicat button email
            if (v::email()->validate($data["email"]) == false) {//Comprovació email backend
                return $this->container->get('view')
                    ->render($response, 'profile.twig', ['error' => -2, 'username' => $this->username, 'birthdate' => $this->birthdate, 'email' => $this->email, 'password' => $this->password, 'logged' => isset($_SESSION["userID"])]);
            }else {
                //Obtenir email actual
                $this->getProfileInfo();//Cal tornar-ho a cridar perquè els atributs fets a la crida del invoke no es queden guardats després d'acabar el invoke

                //Actualitzar email BBDD
                $dataPlus = array(
                    "new-data" => $data,
                    "old-email" => $this->email
                );//Completo l'array del request de data (nomes ve el nou email) amb el email actual

                try {
                    $service = $this->container->get('update_email_use_case');
                    $service($dataPlus);//s'executa servei d'actualitzar email bbdd

                    //Actualitzo email pel nou
                    $this->email = $data['email'];
                } catch (\Exception $e) {
                    return $this->container->get('view')
                        ->render($response, 'profile.twig', ['error' => -2, 'username' => $this->username, 'birthdate' => $this->birthdate, 'email' => $this->email, 'password' => $this->password, 'logged' => isset($_SESSION["userID"])]);
                }

                return $this->container->get('view')
                    ->render($response, 'profile.twig', ['error' => false, 'username' => $this->username, 'birthdate' => $this->birthdate, 'email' => $this->email, 'password' => $this->password, 'logged' => isset($_SESSION["userID"])]);
            }

        }else {
            //Clicat button password
            if (v::stringType()->noWhitespace()->length(6, 12)->validate($data["password"]) == false ||
                v::alnum()->validate($data["password"]) == false ||
                preg_match("#[A-Z]+#", $data["password"]) == false ||
                preg_match("#[a-z]+#", $data["password"]) == false) {//Comprovació password

                return $this->container->get('view')
                    ->render($response, 'profile.twig', ['error' => -4, 'username' => $this->username, 'birthdate' => $this->birthdate, 'password' => $this->password, 'logged' => isset($_SESSION["userID"])]);

            }else if ($data["password"] != $data["confirm-password"]) {//Comprovació confirm password
                    //Check same password
                    return $this->container->get('view')
                        ->render($response, 'profile.twig', ['error' => -5, 'username' => $this->username, 'birthdate' => $this->birthdate, 'password' => $this->password, 'logged' => isset($_SESSION["userID"])]);

            }else {

                //TODO: Actualitzar password BBDD


                return $this->container->get('view')
                    ->render($response, 'profile.twig', ['error' => false, 'username' => $this->username, 'birthdate' => $this->birthdate, 'password' => $this->password, 'logged' => isset($_SESSION["userID"])]);
            }
        }

    }

}