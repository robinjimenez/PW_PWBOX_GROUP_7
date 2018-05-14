<?php
/**
 * Created by PhpStorm.
 * User: Jordi
 * Date: 13/5/18
 * Time: 20:54
 */

namespace PWBox\Controller\Middleware;


use Slim\Http\Request;
use Slim\Http\Response;

class SessionMiddleware
{

    public function __invoke(Request $request, Response $response, callable $next)
    {
        session_start();
        return $next($request, $response);
    }

}