<?php

session_start();

require 'application/lib/Dev.php';
use application\core\Router;

// подгрузка необходимых классов
spl_autoload_register(function($class) {
    $path = str_replace('\\', '/', $class.'.php');
    if (file_exists($path)) {
        require $path;
    }

});


// запуск маршрутизатора
$router = new Router;
$router->run();