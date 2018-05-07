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

    //Opció 1 -- recommended
    public function __invoke(Request $request, Response $response, array $args) {
        try {
            return $this->container->get('view')
                ->render($response, 'register.twig', ['error' => false]);

        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }
    }
}