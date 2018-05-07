<?php

namespace PWBox\Controller\Middleware;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Respect\Validation\Validator as v;


class RegisterValidationsMiddleware {
    /*
    public function __invoke(Request $request, Response $response, $next) {
        $data = $request->getParsedBody();
        $validData = $this->checkRegisterData($data);//Validació dades formulari

        return $response->getBody()->write($validData);
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
    */
}