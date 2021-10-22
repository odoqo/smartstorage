<?php

/*
 * Маршрутизация - использует таблицу маршрутизации, в которой указаны имя контроллера и действие,соответствующие конкретному url
 * соответственно, когда переходим по url адресу, данный контроллер выполняет заданное действие
 */
namespace application\core;

use application\core\View;

class Router
{
    protected $routes = array();
    protected $params = array();
    
    public function __construct() 
    {
        //подключение таблицы
        $routesArr = require 'application/config/routes.php';
        foreach ($routesArr as $route => $params) {
            $this->add($route, $params);
        }
    }

    //для замены маршрута на соответствующее регулярное выражение
    private function add($route, $params)
    {
        $route = '#^'.$route.'$#';
        $this->routes[$route] = $params;
    }

    //проверяем, есть ли введенный url в нвшей таблице маршрутизации
    //и выбираем соответствующий маршрут(его действие и контроллер)
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

    //основная функция маршрутизации
    public function run()
    {
        //проверяем, есть ли введенный url в нвшей таблице маршрутизации
        if ($this->match()) {
            //выбираем нужный нам контроллер
            $class = 'application\controllers\\'.ucfirst($this->params['controller']).'Controller';
            //проверяем существует ли наш класс
            if (class_exists($class)) {
                //выбираем нужное нам действие контроллера
                $action = $this->params['action'].'Action';
                if (method_exists($class, $action)) {
                    //создается обработчик(контроллер)
                    $controller = new $class($this->params);
                    //вызываем введенное действие
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
