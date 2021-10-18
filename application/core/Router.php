<?php

namespace application\core;

use application\core\View;

class Router
{
    protected $routes = array();
    protected $params = array();
    
    public function __construct() 
    {
        $routesArr = require 'application/config/routes.php';
        foreach ($routesArr as $route => $params) {
            $this->add($route, $params);
        }
    }

    private function add($route, $params)
    {
        $route = '#^'.$route.'$#';
        $this->routes[$route] = $params;
    }

    public function match() 
    {
        $url = trim($_SERVER['REQUEST_URI'], '/');
       
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    public function run()
    {
        if ($this->match()) {
            $class = 'application\controllers\\'.ucfirst($this->params['controller']).'Controller';
            if (class_exists($class)) {
                $action = $this->params['action'].'Action';
                if (method_exists($class, $action)) {
                    $controller = new $class($this->params);
                    $controller->$action();
                } else {
                    View::errorCode(404);
                }
            } else {
                View::errorCode(404);
            }
        } else {
            View::errorCode(404);
        }
    }

}