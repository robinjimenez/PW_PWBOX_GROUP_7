<?php
/**
 * Created by PhpStorm.
 * User: Jordi
 * Date: 13/5/18
 * Time: 12:58
 */

namespace PWBox\Controller;

use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

class ProfileController {
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    //Mètode per la crida GET de profile
    public function __invoke(Request $request, Response $response, array $args)
    {

        return $this->container->get('view')
            ->render($response, 'profile.twig', ['error' => false, 'username' => 'Jordi', 'birthdate' => '07/01/1997', 'email' => 'jordi@gmail.com', 'password' => 'dshbchjbchjd']);
    }

    //Mètode per la crida POST de profile
    public function updateProfileAction(Request $request, Response $response) {
        $data = $request->getParsedBody();

        if (isset($data['email-button'])) {
            //Comprovació email
            if (v::email()->validate($data["email"]) == false) {
                return $this->container->get('view')
                    ->render($response, 'profile.twig', ['error' => -2, 'username' => 'Jordi', 'birthdate' => '07/01/1997', 'email' => 'jordi@gmail.com', 'password' => 'dshbchjbchjd']);
            }else {
                return $this->container->get('view')
                    ->render($response, 'profile.twig', ['error' => false, 'username' => 'Jordi', 'birthdate' => '07/01/1997', 'email' => $data["email"], 'password' => 'dshbchjbchjd']);
            }

        }else {
            //Comprovació password
            if (v::stringType()->noWhitespace()->length(6, 12)->validate($data["password"]) == false ||
                v::alnum()->validate($data["password"]) == false ||
                preg_match("#[A-Z]+#", $data["password"]) == false ||
                preg_match("#[a-z]+#", $data["password"]) == false) {

                return $this->container->get('view')
                    ->render($response, 'profile.twig', ['error' => -4, 'username' => 'Jordi', 'birthdate' => '07/01/1997', 'password' => 'dshbchjbchjd']);

            }else if ($data["password"] != $data["confirm-password"]) {
                    //Check same password
                    return $this->container->get('view')
                        ->render($response, 'profile.twig', ['error' => -5, 'username' => 'Jordi', 'birthdate' => '07/01/1997', 'password' => 'dshbchjbchjd']);

            }else {
                return $this->container->get('view')
                    ->render($response, 'profile.twig', ['error' => false, 'username' => 'Jordi', 'birthdate' => '07/01/1997', 'password' => $data["password"]]);
            }
        }

    }

}