<?php

return array(

	//главная страница
	'smartstorage' => array(
		'controller' => 'main',
		'action' 	 => 'index',
	),

	// страницы авторизации
	'smartstorage/login' => array(
		'controller' => 'account',
		'action' 	 => 'login',
	),

	// страница регистрации
	'smartstorage/register' => array(
		'controller' => 'account',
		'action' 	 => 'register',
	),

	// профиль
	'smartstorage/storage' => array(
		'controller' => 'storage',
		'action' 	 => 'profile',
	),

	// 
	'smartstorage/storages' => array(
		'controller' => 'profile',
		'action' 	 => 'storages',
	),
	
	// страница создания
	'smartstorage/create' => array(
		'controller' => 'profile',
		'action' 	 => 'create',
	),



);