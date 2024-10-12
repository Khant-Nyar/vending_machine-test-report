<?php

namespace App\Middleware;

use bootstrap\Framework\Request;
use bootstrap\Framework\Router\MiddlewareInterface;
use bootstrap\Framework\Session;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next)
    {
        if (!Session::has('user')) {
            header('Location: /login');
            exit;
        }

        return $next($request);
    }
}
