
<?php

use App\Controllers\Auth\AuthController;
use App\Controllers\ProductsController;
use App\Middleware\AuthMiddleware;
use bootstrap\Framework\Router\Route;

Route::get('/', function () {
    redirect('register');
});


Route::get('/register', fn() => view('auth/register'));
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', fn() => view('auth/login'));
Route::post('/login', [AuthController::class, 'login']);

Route::get('/products', [ProductsController::class, 'index']);
Route::get('/products/{slug}', [ProductsController::class, 'show']);
Route::post('/products/create', [ProductsController::class, 'create']);
Route::get('/products/{slug}/edit', [ProductsController::class, 'edit']);
Route::put('/products/{slug}', [ProductsController::class, 'update']);
Route::delete('/products/{slug}', [ProductsController::class, 'delete']);
Route::post('/products/purchase/{slug}', [ProductsController::class, 'purchase']);


Route::delete('/logout', [AuthController::class, 'logout']);


// Route::get('/products', [ProductsController::class, 'index']);
