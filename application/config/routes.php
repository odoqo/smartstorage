<?php


/*
 * таблица маршрутизации: построена по принципу: 
 * [url] => array(
		'controller' => 'nameOfController',
		'action'     => 'nameOfAction',
	),
 */
return array(

	//главная страница
	'smartstorage' => array(
		'controller' => 'account',
		'action'     => 'login',
	),

	// страницы авторизации
	'smartstorage/login' => array(
		'controller' => 'account',
		'action'     => 'login',
	),

	// страница регистрации
	'smartstorage/register' => array(
		'controller' => 'account',
		'action'     => 'register',
	),

	// выход из аккаунта
	'smartstorage/logout' => array(
		'controller' => 'account',
		'action'     => 'logout',
	),
	// профиль
	'smartstorage/profile' => array(
		'controller' => 'storage',
		'action'     => 'profile',
	),
    
    	// добавление файла
	'smartstorage/addf' => array(
		'controller' => 'storage',
		'action'     => 'addf',
	),
    
        // добавление каталога
	'smartstorage/addc' => array(
		'controller' => 'storage',
		'action'     => 'addc',
	),
    
            // перейти на уровень назад
	'smartstorage/back' => array(
		'controller' => 'storage',
		'action'     => 'back',
	),

	//TEST
	'smartstorage/test' => array(
		'controller' => 'test',
		'action'     => 'test',
	),
);