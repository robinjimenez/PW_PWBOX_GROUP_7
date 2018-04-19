<?php

namespace PWBox\Controller\Middleware;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class UserLoggedMiddleware
{
    public function __invoke(Request $request, Response $response, callable $next) {
        if (!isset($_SESSION['user_id'])) {
            return $response->withStatus(302)->withHeader('Location','/');
        }
        return $next($request, $response);
    }
}