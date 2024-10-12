<?php

use bootstrap\Framework\Database\Query;
use bootstrap\Framework\Request;
use bootstrap\Framework\Router\Route;
use bootstrap\Framework\Router\RouterDispatcher;

const BASE_PATH = __DIR__ . '/../';

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require BASE_PATH . 'vendor/autoload.php';

require BASE_PATH . 'routes/web.php';


$requestMethod = (new Request())->getMethod();
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

echo Route::dispatch($requestUri, $requestMethod);
