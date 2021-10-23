<?php

return array(

	// главная страница
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
		'action'     => 'profile',
	),

	// выход из аккаунта
	'smartstorage/logout' => array(
		'controller' => 'account',
		'action' 	 => 'logout',
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
	'smartstorage/levelUp' => array(
		'controller' => 'storage',
		'action'     => 'levelUp',
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
	'smartstorage/changeLocation' => array(
		'controller' => 'storage',
		'action'     => 'changeLocation',
	),
    
    // добавление файла
	'smartstorage/downloadFile' => array(
		'controller' => 'storage',
		'action'     => 'downloadFile',
	),      
);