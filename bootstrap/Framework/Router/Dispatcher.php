<?php

namespace bootstrap\Framework\Router;

class Dispatcher
{
    private $requestMethod;
    private $requestUri;

    public function __construct($requestMethod, $requestUri)
    {
        $this->requestMethod = $requestMethod;
        $this->requestUri = $requestUri;
    }

    public function dispatch()
    {
        $routes = Route::getRoutes();

        if (isset($routes[$this->requestMethod])) {
            foreach ($routes[$this->requestMethod] as $route => $action) {
                if ($route === $this->requestUri) {
                    return $this->executeAction($action);
                }
            }
        }

        http_response_code(404);
        echo '404 Not Found';
    }

    private function executeAction($action)
    {
        if (is_callable($action)) {
            return $action(); // Execute closure
        }

        list($controller, $method) = explode('@', $action);
        $controllerInstance = new $controller();
        return $controllerInstance->$method(); // Call the controller method
    }
}
