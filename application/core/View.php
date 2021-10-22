<?php

//основное отображение страниц

namespace application\core;

use application\lib\Template;

class View
{
	public $path;
	public $route;

	public function __construct($route) 
	{
		$this->route = $route;
		$this->path  = $route['controller'].'/'.$route['action'];
	}

        //вывод основной страницы по шаблону $templateCode 
        //в него вноситься $__dataArray
	public function generate($__dataArray=array()) 
	{
		$path = 'application/views/'.$this->path.'.html';
		if (file_exists($path)) {
			$templateCode = file_get_contents($path);
			echo Template::build($templateCode, $__dataArray);
		} else {
			View::errorCode(404);
		}
	}

        //переадресация на $url
	public function redirect($url) 
	{
		header('location: '.$url);
		exit;
	}

	public static function errorCode($code) 
	{
		http_response_code($code);
		$path = 'application/views/errors/'.$code.'.php';
		if (file_exists($path)) {
			require $path;
		}
		exit;
	}
}	