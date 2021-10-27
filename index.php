<?php

session_start();

use application\core\Router;
use application\core\View;

// установка функции автозагрузки классов
spl_autoload_register(function($class) {
    $path = str_replace('\\', '/', $class.'.php');
    if (file_exists($path)) {
        require $path;
    }

});

$router = new Router;

// запуск маршрутизатора
try {
    $router->run();
} catch (Exception $ex) {
    View::exception($ex);   
}