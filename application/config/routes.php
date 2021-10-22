<?php

return array(

	//главная страница
	'smartstorage' => array(
		'controller' => 'account',
		'action' 	 => 'login',
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

	// выход из аккаунта
	'smartstorage/logout' => array(
		'controller' => 'account',
		'action' 	 => 'logout',
	),
	// профиль
	'smartstorage/profile' => array(
		'controller' => 'storage',
		'action' 	 => 'profile',
	),

	//
	'smartstorage/users' => array(
		'controller' => 'storage',
		'action' 	 => 'users',
	),

	//
	'smartstorage/user' => array(
		'controller' => 'storage',
		'action' 	 => 'user',
	),

	//
	'smartstorage/levelUp' => array(
		'controller' => 'storage',
		'action' 	 => 'levelUp',
	),

	//
	'smartstorage/newFile' => array(
		'controller' => 'storage',
		'action' 	 => 'newFile',
	),

	//
	'smartstorage/newCatalog' => array(
		'controller' => 'storage',
		'action' 	 => 'newCatalog',
	),

	//
	'smartstorage/data' => array(
		'controller' => 'storage',
		'action' 	 => 'data',
	),

	//
	'smartstorage/test' => array(
		'controller' => 'test',
		'action' 	 => 'test',
	),

);