<?php

session_start();

use application\core\Router;

// установка функции автозагрузки классов
spl_autoload_register(function($class) {
    $path = str_replace('\\', '/', $class.'.php');
    if (file_exists($path)) {
        require $path;
    }

});

// запуск маршрутизатора
$router = new Router;
$router->run();