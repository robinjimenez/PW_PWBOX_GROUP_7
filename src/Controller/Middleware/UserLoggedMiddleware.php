<?php

namespace PWBox\Controller\Middleware;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;


class UserLoggedMiddleware
{
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, callable $next) {
        if (isset($_SESSION["userID"])) {
            return $next($request, $response);
        }else {
            return $this->container->get('view')
                ->render($response->withStatus(403), 'error.twig', []);

        }
    }
}