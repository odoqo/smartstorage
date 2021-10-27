<?php

namespace application\core;

use application\core\View;

abstract class Controller 
{
	public $route;
	public $view;
	public $model;

	public function __construct($route)
	{
		// параметры маршрута по которому был вызван контроллер
		// (параметры определенного маршрута указаны в application/config/routes.php)
		$this->route = $route;

		// подключение соответствующего вида и модели контроллера 
		$this->view  = new View($route);
		$this->model = $this->loadModel($route['controller']);
	}

	/**
	 * Загрузка соответствующей модели
	 */
	public function loadModel($name) 
	{
		$path = 'application\models\\'.ucfirst($name);
		if (class_exists($path)) {
			return new $path;
		}
	}
}