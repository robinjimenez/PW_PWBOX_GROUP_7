<?php

namespace PWBox\Controller\Middleware;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Respect\Validation\Validator as v;


class LoginValidationsMiddleware {

    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, $next) {
        $data = $request->getParsedBody();
        $validData = $this->checkLoginData($data);

        if ($validData != 1) {
            try {
                //En cas d'error, el middleware atura la ruta i retorna per renderitzar la pàgina de login amb error
                return $this->container->get('view')
                    ->render($response, 'login.twig', ['error' => true]);
            } catch (NotFoundExceptionInterface $e) {
            } catch (ContainerExceptionInterface $e) {
            }
        }

        //Si no hi ha error es passa al següent middleware
        //o si no hi ha més es passa a loginAction de PostUserController
        return $next($request, $response);
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