<?php

use bootstrap\Framework\Response;
use bootstrap\Framework\Session;

if (!function_exists('dd')) {
    /**
     * Dump and die.
     *
     * @param mixed $value
     */
    function dd($value)
    {
        echo "<pre>";
        var_dump($value);
        echo "</pre>";
        die();
    }
}

if (!function_exists('urlIs')) {
    /**
     * Check if the current URL matches the given value.
     *
     * @param string $value
     * @return bool
     */
    function urlIs($value)
    {
        return $_SERVER['REQUEST_URI'] === $value;
    }
}

if (!function_exists('abort')) {
    /**
     * Abort the request and return the specified HTTP status code.
     *
     * @param int $code
     */
    function abort($code = 404)
    {
        http_response_code($code);
        require("Views/{$code}.php");
        die();
    }
}

if (!function_exists('authorize')) {
    /**
     * Check if a condition is true, abort otherwise.
     *
     * @param bool $condition
     * @param int $status
     * @return bool
     */
    function authorize($condition, $status = Response::FORBIDDEN)
    {
        if (!$condition) {
            abort($status);
        }

        return true;
    }
}

// Base path helper function
if (!function_exists('base_path')) {
    function base_path($path = '')
    {
        return dirname(__DIR__, 2) . ($path ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : '');
    }
}

// Public path helper function
if (!function_exists('public_path')) {
    function public_path($path = '')
    {
        return base_path('public') . DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR);
    }
}

// Storage path helper function
if (!function_exists('storage_path')) {
    function storage_path($path = '')
    {
        return base_path('storages') . DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR);
    }
}

// Render a view with a master layout and attributes
if (!function_exists('view')) {
    function view($path, $attributes = [])
    {
        extract($attributes);
        ob_start(); // Start output buffering
        $filePath = base_path('resources/views/' . $path . '.php');
        if (!file_exists($filePath)) {
            throw new \Exception("View file not found: {$filePath}");
        }
        require $filePath;
        return ob_get_clean();
    }
}


if (!function_exists('include_partial')) {
    function include_partial($path, $attributes = [])
    {
        $partialPath = base_path('resources/views/partials/' . trim($path, DIRECTORY_SEPARATOR) . '.php');
        if (!file_exists($partialPath)) {
            throw new \Exception("Partial view not found: {$partialPath}");
        }
        extract($attributes);
        require $partialPath;
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect to a specified path.
     *
     * @param string $path
     */
    function redirect($path)
    {
        header("Location: {$path}");
        exit();
    }
}

if (!function_exists('old')) {
    /**
     * Get old input value.
     *
     * @param string $key
     * @param string $default
     * @return mixed
     */
    function old($key, $default = '')
    {
        return Session::get('old')[$key] ?? $default;
    }
}

if (!function_exists('config')) {
    /**
     * Get a configuration value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function config(string $key, $default = null)
    {
        $segments = explode('.', $key);
        $file = array_shift($segments);
        $path = base_path('config' . DIRECTORY_SEPARATOR . $file . '.php');

        if (!file_exists($path)) {
            return $default;
        }

        static $configs = [];
        if (!isset($configs[$file])) {
            $configs[$file] = require $path;
        }

        $value = $configs[$file];
        foreach ($segments as $segment) {
            if (is_array($value) && isset($value[$segment])) {
                $value = $value[$segment];
            } else {
                return $default;
            }
        }

        return $value;
    }

    function checkRole($requiredRole): bool
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === $requiredRole;
    }




    if (!function_exists('session')) {
        function session(): Session
        {
            return new Session();
        }
    }

    function prettyPrint($data)
    {
        echo '<pre>';
        if (is_array($data) || is_object($data)) {
            print_r($data);
        } else {
            echo $data;
        }
        echo '</pre>';
    }
}
