<?php

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