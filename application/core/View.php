<?php

namespace application\core;

use application\lib\Template;

/**
 * Логика генерации страниц
 */
class View
{
 	public $path;
	public $route;

	public function __construct(array $route) 
	{
		$this->route = $route;
		$this->path  = $route['controller'].'/'.$route['action'];
	}

	/**
	 * Генерация страницы использую соответствующий шаблон 
	 */
	public function generate($__dataArray=array()) 
	{
		$path = 'application/views/'.$this->path.'.html';
		if (file_exists($path)) {
			$templateCode = file_get_contents($path);
			echo Template::build($templateCode, $__dataArray);
			exit;
		} else {
			View::errorCode(404);
		}
	}

	/**
	 * Перенаправление пользователя на страницу с url = $__url
	 */
	public function redirect($__url) 
	{
		header('location: '.$__url);
		exit;
	}

	/**
	 * Обработка исключения $__exception
	 * 
	 * @param $__exception Исключение требующее обработки
	 */
	public static function exception($__exception)
	{
		$templateCode = file_get_contents('application/views/exceptions/exception.html');
		$message  	  = ['message' => $__exception->getMessage()];
		echo Template::build($templateCode, $message);
		exit;
	}

	/**
	 * Страница ошибки с кодом $__code
	 */
	public static function errorCode($__code) 
	{
		http_response_code($__code);
		$path = 'application/views/errors/'.$__code.'.php';
		if (file_exists($path)) {
			require $path;
		}
		exit;
	}

	/**
	 * Переход на указанную страницу сайта
	 */
	public function jumpOnPage($__page='')
	{
		$this->redirect("http://localhost/smartstorage/$__page/");
	}
}	