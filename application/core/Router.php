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

    /**
     * Замена назаваний маршрутов на их регулярные выражения
     */
    private function add($route, $params)
    {
        $route = '#^'.$route.'$#';
        $this->routes[$route] = $params;
    }

    /**
     * Проверка маршрута по текущему url на соответствие
     */
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

    /**
     * Поиск нужного обработчика маршрута
     */
    public function run()
    {
        if ($this->match()) {
        
            // класс обработчика
            $class = 'application\controllers\\'.ucfirst($this->params['controller']).'Controller';
            if (class_exists($class)) {
        
                // обработчик
                $action = $this->params['action'].'Action';
                if (method_exists($class, $action)) {
                    
                    // запуск обработчика
                    $controller = new $class($this->params);
                    $controller->$action();
                }
            }
        }
        View::errorCode(404);
    }
}