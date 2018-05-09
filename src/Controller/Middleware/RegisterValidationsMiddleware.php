<?php

namespace PWBox\Controller\Middleware;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Respect\Validation\Validator as v;


class RegisterValidationsMiddleware {

    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, $next) {
        $data = $request->getParsedBody();
        $validData = $this->checkRegisterData($data);//Validació dades formulari

        if ($validData != 1) {
            try {
                //En cas d'error, el middleware atura la ruta i retorna per renderitzar la pàgina de registre amb error
                return $this->container->get('view')
                    ->render($response, 'register.twig', ['error' => $validData]);
            } catch (NotFoundExceptionInterface $e) {
            } catch (ContainerExceptionInterface $e) {
            }
        }

        //Si no hi ha error es passa al següent middleware
        //o si no hi ha més es passa a registerAction de PostUserController
        return $next($request, $response);
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
}