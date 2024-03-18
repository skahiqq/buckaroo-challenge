<?php

use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\NotFoundController;
use App\Controllers\RegisterController;
use App\Router;
use App\Controllers\UserController;

$router = new Router();

$router->get('/', LoginController::class, 'index');
$router->get('/home', HomeController::class, 'index');
$router->get('/login', LoginController::class, 'index');
$router->post('/login', LoginController::class, 'login');
$router->get('/logout', UserController::class, 'logout');
$router->get('/register', RegisterController::class, 'index');
$router->post('/user/login', UserController::class, 'login');
$router->post('/user/store', UserController::class, 'store');
$router->post('/user/register', RegisterController::class, 'store');
$router->get('/not-found', NotFoundController::class, 'index');

$router->dispatch();