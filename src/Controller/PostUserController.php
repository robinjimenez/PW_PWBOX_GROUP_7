<?php

namespace PWBox\Controller;

use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

class PostUserController {

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /*
    public function indexAction(Request $request, Response $response) {
        $messages = $this->container->get('flash')->getMessages();
        $registerMessages = isset($messages['register'])?$messages['register']:[];
        return $this->container->get('view')
            ->render($response, 'register.twig', ['messages' => $registerMessages,]);
    }*/

    public function registerAction(Request $request, Response $response) {

        try {
            $data = $request->getParsedBody();
            $validData = $this->checkRegisterData($data);//Validació dades formulari
            //var_dump($validData);

            // TODO: Register User To Database:
            /*if ($validData == 1) {

            $service = $this->container->get('post_user_use_case');
            $service($data);

            $this->container->get('flash')->addMessage('register','User registered');
            return $response->withStatus(302)->withHeader('Location','/user');*/
        } catch (\Exception $e)  {
            /*
            echo $e->getMessage();
            die();
            return $this->container->get('view')
                ->render($response, 'register.twig', []);
            */
        }
        return $response;
    }

    public function loginAction(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $validData = $this->checkLoginData($data);
        //var_dump($validData);

        // TODO: Check if user exist in database and redirect to dashboard
    }

    public function checkRegisterData(Array $data) {
        $validData = 1;//1 is OK
        //var_dump($data);

        //Check username
        if (v::alnum()->noWhitespace()->validate($data["username"]) == false ||
            v::stringType()->length(1, 20)->validate($data["username"]) == false) {
            $validData = -1;
        }

        //Check email
        if (v::email()->validate($data["email"]) == false) {
            $validData = -2;
        }

        //Check birthdate
        if (v::date()->validate($data["birthdate"]) == false) {
            $validData = -3;
        }

        //Check password
        if (v::stringType()->noWhitespace()->length(6, 12)->validate($data["password"]) == false ||
            v::alnum()->validate($data["password"]) == false ||
            preg_match("#[A-Z]+#", $data["password"]) == false ||
            preg_match("#[a-z]+#", $data["password"]) == false) {
            $validData = -4;
        }

        //Check same password
        if ($data["password"] != $data["confirm-password"]) {
            $validData = -5;
        }

        return $validData;
    }

    public function checkLoginData(Array $data) {
        $validData = 1;//1 is OK

        //Check email
        if (v::email()->validate($data["email"]) == false) {
            $validData = -1;
        }

        //Check password
        if (v::stringType()->noWhitespace()->length(6, 12)->validate($data["password"]) == false ||
            v::alnum()->validate($data["password"]) == false ||
            preg_match("#[A-Z]+#", $data["password"]) == false ||
            preg_match("#[a-z]+#", $data["password"]) == false) {
            $validData = -2;
        }

        return $validData;
    }

}