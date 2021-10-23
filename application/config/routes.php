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
	'smartstorage/newFile' => array(
		'controller' => 'storage',
		'action'     => 'newFile',
	),
    
        // добавление каталога
	'smartstorage/newCatalog' => array(
		'controller' => 'storage',
		'action'     => 'newCatalog',
	),
    
        // перейти на уровень назад
	'smartstorage/back' => array(
		'controller' => 'storage',
		'action'     => 'back',
	),
    
        // удаление файла
	'smartstorage/deleteFile' => array(
		'controller' => 'storage',
		'action'     => 'deleteFile',
	),
    
         // удаление каталога
	'smartstorage/deleteCatalog' => array(
		'controller' => 'storage',
		'action'     => 'deleteCatalog',
	),
    
        //переход в каталог
	'smartstorage/changeCatalog' => array(
		'controller' => 'storage',
		'action'     => 'changeCatalog',
	),
    
        // добавление файла
	'smartstorage/downloadFile' => array(
		'controller' => 'storage',
		'action'     => 'downloadFile',
	),
    
         // профиль
	'smartstorage/users' => array(
		'controller' => 'storage',
		'action'     => 'users',
	),

	//TEST
	'smartstorage/test' => array(
		'controller' => 'test',
		'action'     => 'test',
	),
);