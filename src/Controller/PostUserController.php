<?php

namespace PWBox\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/*
 * Classe que conté els mètodes a executar en cada cas quan es reben crides POST de l'usuari
 */
class PostUserController {

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function registerAction(Request $request, Response $response) {
        //Si s'arriba aqui és perquè els middlewares no han aturat l'execució de la ruta abans

        //Password encryption:
        $data = $request->getParsedBody();
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['password'] = $hashed_password;

        try {
            //Register User To Database:
            $service = $this->container->get('post_user_use_case');
            $service($data);

        } catch (\Exception $e) {
            try {
                $response = $this->container->get('view')
                    ->render($response, 'register.twig', ['error' => -6]);
            } catch (NotFoundExceptionInterface $e) {
            } catch (ContainerExceptionInterface $e) {
            }

            return $response;
        }

        try {
            //Un cop executades totes les accions de registre sense errors es renderitza la pàgina de login
            return $this->container->get('view')
                ->render($response, 'login.twig', ['error' => false]);
        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }
    }

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
            $requestPassword = $data['password'];
            $ddbbPassword = $ddbbServiceResult[0]['password'];

            if(password_verify($requestPassword, $ddbbPassword)) {
                //Password correcte:
                echo("Password correcte");
                //Iniciem sessió amb el email de l'usuari
                session_start();
                $data = $request->getParsedBody();
                $_SESSION["userID"] = $data["email"];

                try {
                    //Un cop executades totes les accions de registre sense errors es renderitza la pàgina de dashboard TODO: Dashboard page
                    return "User Logged In" . "<br>" . "La sessió és de: " . $_SESSION["userID"] . "<br>";
                    //return $this->container->get('view')->render($response, 'login.twig', ['logged' => isset($_SESSION)]);
                } catch (NotFoundExceptionInterface $e) {
                } catch (ContainerExceptionInterface $e) {
                }
            }else {
                return $this->container->get('view')->render($response, 'login.twig', ['error' => true]);
            }
        }else {
            return $this->container->get('view')->render($response, 'login.twig', ['error' => true]);
        }
    }
}