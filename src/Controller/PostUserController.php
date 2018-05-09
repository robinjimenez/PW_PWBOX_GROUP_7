<?php

namespace PWBox\Controller;

use function MongoDB\BSON\toJSON;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

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
            $response = $response
                -> withStatus(500)
                -> withHeader('Content-Type', 'text/html')
                -> write("Something went wrong");
            return $response;
        }

        try {
            //Un cop executades totes les accions de registre sense errors es renderitza la pàgina de login
            return $this->container->get('view')
                ->render($response, 'login.twig');
        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }
    }

    public function loginAction(Request $request, Response $response) {
        //Si s'arriba aqui és perquè els middlewares no han aturat l'execució de la ruta abans

        // TODO: Check if user exists in database and redirect to dashboard
        // Query the database for username and password
        // ...

        //if(password_verify($password, $hashed_password)) {
            // If the password inputs matched the hashed password in the database
            // Do something, you know... log them in.
        //}

        // Else, Redirect them back to the login page.


        try {
            //Un cop executades totes les accions de registre sense errors es renderitza la pàgina de dashboard TODO: De moment es la de login
            return $this->container->get('view')
                ->render($response, 'login.twig', ['error' => false]);
        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }
    }

    /*
    public function indexAction(Request $request, Response $response) {
        $messages = $this->container->get('flash')->getMessages();
        $registerMessages = isset($messages['register'])?$messages['register']:[];
        return $this->container->get('view')
            ->render($response, 'register.twig', ['messages' => $registerMessages,]);
    }*/

}