<?php

namespace bootstrap\Framework\Router;

use bootstrap\Framework\Request;

class RouterDispatcher
{
    public function executeAction($action, $params = [])
    {
        $request = new Request();

        if (is_callable($action)) {
            return $action(...$params);
        }

        if (is_array($action) && count($action) === 2) {
            list($controller, $method) = $action;

            if (!class_exists($controller)) {
                return "Controller class {$controller} not found.";
            }

            $controllerInstance = new $controller();

            if (!method_exists($controllerInstance, $method)) {
                return "Method {$method} not found in controller {$controller}.";
            }

            return $controllerInstance->$method($request, ...$params);
        }

        return "Invalid action specified.";
    }
}
