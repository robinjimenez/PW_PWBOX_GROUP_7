<?php
namespace PWBox\Controller;

use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;


class LoginController {
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    //Per la crida get
    public function __invoke(Request $request, Response $response, array $args)
    {
        return $this->container->get('view')
            ->render($response, 'login.twig', ['error' => false, 'logged' => isset($_SESSION["userID"])]);
    }

    //Per la crida post
    public function loginAction(Request $request, Response $response) {
        //Si s'arriba aqui és perquè els middlewares no han aturat l'execució de la ruta abans

        //Check if user exists in database:

        $data = $request->getParsedBody();

        //Recuperem la informació de la bbdd amb un servei
        $service = $this->container->get('login_user_use_case');//1)Recuperem de dependencies la funció associada a login_user_use_case

        //Utilitzem el servei passant-li les dades de la request (formualari) per obtenir resultats
        //$ddbbServiceResult: La Query mira si existeix usuari amb el email introduït en el formualari. Retorna el resultat de la Query, que és $ddbbServiceResult.
        $ddbbServiceResult = $service($data);//2)Cridem a la funció associada a login_user_use_case, li passem les dades del formulari. //9)Recuperem resultat de la query

        if (count($ddbbServiceResult) > 0) {//Si tenim resultats (el email existeix a la bbdd)

            //Comprovem contrasenyes iguals
            $requestPassword = $data['password'];//password form
            $ddbbPassword = $ddbbServiceResult[0]['password'];//password bbdd

            $encryptionService = $this->container->get('model_encryption_service');
            $encryptedPassword = $encryptionService("encrypt", $requestPassword);

            if($ddbbPassword == $encryptedPassword) {
                //Password correcte:

                //Iniciem sessió amb el username de l'usuari
                $_SESSION["userID"] = $ddbbServiceResult[0]['username'];

                try {
                    //Un cop executades totes les accions de registre sense errors es renderitza la pàgina de dashboard
                    //return "User Logged In" . "<br>" . "La sessió és de: " . $_SESSION["userID"] . "<br>";
                    return $this->container->get('view')->render($response, 'dash.twig', ['logged' => isset($_SESSION["userID"])]);
                } catch (NotFoundExceptionInterface $e) {
                } catch (ContainerExceptionInterface $e) {
                }
            }else {
                return $this->container->get('view')->render($response, 'login.twig', ['error' => true, 'logged' => isset($_SESSION["userID"])]);
            }
        }else {
            return $this->container->get('view')->render($response, 'login.twig', ['error' => true, 'logged' => isset($_SESSION["userID"])]);
        }
    }
}