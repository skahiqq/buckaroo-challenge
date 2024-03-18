<?php

namespace App;

use App\Controllers\Controller;

class Router extends Controller
{
    /**
     * Here we store routes
     * @var array
     */
    protected array $routes = [];

    /**
     * @param $route
     * @param $controller
     * @param $action
     * @param $method
     * @return void
     */
    private function addRoute($route, $controller, $action, $method): void
    {

        $this->routes[$method][$route] = ['controller' => $controller, 'action' => $action];
    }

    /**
     * @param $route
     * @param $controller
     * @param $action
     * @return void
     */
    public function get($route, $controller, $action): void
    {
        $this->addRoute($route, $controller, $action, "GET");
    }

    /**
     * @param $route
     * @param $controller
     * @param $action
     * @return void
     */
    public function post($route, $controller, $action): void
    {
        $this->addRoute($route, $controller, $action, "POST");
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function dispatch(): void
    {
        $uri = strtok($_SERVER['REQUEST_URI'], '?');
        $method =  $_SERVER['REQUEST_METHOD'];

        if (array_key_exists($uri, $this->routes[$method])) {
            $controller = $this->routes[$method][$uri]['controller'];
            $action = $this->routes[$method][$uri]['action'];

            $controller = new $controller();
            $controller->$action();
        } else {
            $this->render('404');
        }
    }
}