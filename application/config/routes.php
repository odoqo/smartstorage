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

	// профиль
	'smartstorage/profile' => array(
		'controller' => 'storage',
		'action' 	 => 'profile',
	),

	//TEST
	'smartstorage/test' => array(
		'controller' => 'test',
		'action' 	 => 'test',
	),
);