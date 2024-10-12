<?php

namespace bootstrap\Framework\Router;

use bootstrap\Framework\Request;
use bootstrap\Framework\Router\RouterDispatcher;

class Route
{
    private static $routes = [];
    private static $namedRoutes = [];

    public static function addRoute($method, $route, $action, $name = null, $middleware = null)
    {
        // dd($method);
        self::$routes[$method][$route] = ['action' => $action, 'middleware' => $middleware];
        if ($name) {
            self::$namedRoutes[$name] = $route;
        }
    }
    public static function get($route, $action, $name = null, $middleware = null)
    {
        self::addRoute('GET', $route, $action, $name, $middleware);
    }

    public static function post($route, $action, $name = null, $middleware = null)
    {
        self::addRoute('POST', $route, $action, $name, $middleware);
    }

    public static function put($route, $action, $name = null, $middleware = null)
    {
        self::addRoute('PUT', $route, $action, $name, $middleware);
    }

    public static function patch($route, $action, $name = null, $middleware = null)
    {
        self::addRoute('PATCH', $route, $action, $name, $middleware);
    }


    public static function delete($route, $action, $name = null, $middleware = null)
    {
        self::addRoute('DELETE', $route, $action, $name, $middleware);
    }


    public static function dispatch($uri, $method)
    {
        $routes = self::$routes[$method] ?? [];

        // prettyPrint($uri);
        // prettyPrint($method);
        // prettyPrint($routes);

        foreach ($routes as $route => $routeData) {
            $routePattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $route);
            $routePattern = '#^' . $routePattern . '$#';

            if (preg_match($routePattern, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                if (isset($routeData['middleware']) && $routeData['middleware']) {
                    foreach ($routeData['middleware'] as $middleware) {
                        (new $middleware())->handle(new Request(), function () {});
                    }
                }
                return (new RouterDispatcher())->executeAction($routeData['action'], $params);
            }
        }

        return "404 Not Found";
    }


    public static function getNamedRoute($name, $params = [])
    {
        dd($name);
        $route = self::$namedRoutes[$name] ?? null;
        if (!$route) {
            return null;
        }

        return preg_replace_callback('/\{(\w+)\}/', function ($matches) use ($params) {
            return $params[$matches[1]] ?? $matches[0];
        }, $route);
    }

    public static function getRoutes()
    {
        return self::$routes;
    }
}
