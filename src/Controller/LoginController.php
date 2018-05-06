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

    //Opció 1 -- recommended
    public function __invoke(Request $request, Response $response, array $args)
    {
        return $this->container->get('view')
            ->render($response, 'login.twig', []);
    }
}