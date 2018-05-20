<?php
namespace PWBox\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;


class RegisterController {
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    //Per la crida get
    public function __invoke(Request $request, Response $response, array $args) {
        try {
            return $this->container->get('view')
                ->render($response, 'register.twig', ['error' => false, 'logged' => isset($_SESSION["userID"])]);

        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }
    }

    //Per la crida post
    public function registerAction(Request $request, Response $response) {
        //Si s'arriba aqui és perquè els middlewares no han aturat l'execució de la ruta abans

        //Password encryption:
        $data = $request->getParsedBody();

        $encryptionService = $this->container->get('model_encryption_service');
        $encryptedPassword = $encryptionService("encrypt", $data['password']);
        $data['password'] = $encryptedPassword;

        try {
            //Register User To Database:
            $service = $this->container->get('post_user_use_case');
            $service($data);

        } catch (\Exception $e) {
            //die(var_dump($e));
            try {
                $response = $this->container->get('view')
                    ->render($response, 'register.twig', ['error' => -6, 'logged' => isset($_SESSION["userID"])]);
            } catch (NotFoundExceptionInterface $e) {
            } catch (ContainerExceptionInterface $e) {
            }

            return $response;
        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }

        try {
            //Un cop executades totes les accions de registre sense errors es renderitza la pàgina de login
            return $this->container->get('view')
                ->render($response, 'login.twig', ['error' => false, 'logged' => isset($_SESSION["userID"])]);
        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }
    }
}