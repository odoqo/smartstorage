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

	// выход из аккаунта
	'smartstorage/logout' => array(
		'controller' => 'account',
		'action' 	 => 'logout',
	),

    // профиль
    'smartstorage/profile' => array(
		'controller' => 'storage',
		'action'     => 'profile',
		'page'		 => 'profile'
	),

	// возвращение 
	'smartstorage/home' => array(
		'controller' => 'storage',
		'action' 	 => 'home',
	),

	// добавление файла
	'smartstorage/profile/newFile' => array(
		'controller' => 'storage',
		'action'     => 'newFile',
		'page'		 => 'profile'
	),
    
	// добавление каталога
	'smartstorage/profile/newCatalog' => array(
		'controller' => 'storage',
		'action'     => 'newCatalog',
		'page'		 => 'profile'
	),
    
    // перейти на уровень назад
	'smartstorage/profile/levelUp' => array(
		'controller' => 'storage',
		'action'     => 'levelUp',
		'page'		 => 'profile'
	),
    
    // удаление файла
	'smartstorage/profile/deleteFile' => array(
		'controller' => 'storage',
		'action'     => 'deleteFile',
		'page'		 => 'profile'
	),
    
    // удаление каталога
	'smartstorage/profile/deleteCatalog' => array(
		'controller' => 'storage',
		'action'     => 'deleteCatalog',
		'page'		 => 'profile'
	),
    
	//переход в каталог
	'smartstorage/profile/changeLocation' => array(
		'controller' => 'storage',
		'action'     => 'changeLocation',
		'page'		 => 'profile'
	),
    
    // добавление файла
	'smartstorage/profile/downloadFile' => array(
		'controller' => 'storage',
		'action'     => 'downloadFile',
		'page'		 => 'profile'
	),     

	'smartstorage/users' => array(
		'controller' => 'storage',
		'action'     => 'users',
		'page'		 => 'users'
	),      

	'smartstorage/users/levelUp' => array(
		'controller' => 'storage',
		'action'     => 'levelUp',
		'page'		 => 'users'
	),
	
	'smartstorage/users/changeLocation' => array(
		'controller' => 'storage',
		'action'     => 'changeLocation',
		'page'		 => 'users'
	),
	
	'smartstorage/users/downloadFile' => array(
		'controller' => 'storage',
		'action'     => 'downloadFile',
		'page'		 => 'users'
	),
	
	
	'smartstorage/test' => array(
		'controller' => 'test',
		'action'     => 'test',
	),     
);