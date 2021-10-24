<?php

/**
 * Маршрутизация 
 */
return array(

	// главная страница - страница входа
	'smartstorage' => array(
		'controller' => 'account',
		'action' 	 => 'login',
	),

	// страница входа
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

    // профиль пользователя
    'smartstorage/profile' => array(
		'controller' => 'storage',
		'action'     => 'profile',
		'page'		 => 'profile'
	),

	// возвращение в свое хранилище
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
    
    // переход на уровень вверх(в профиле)
	'smartstorage/profile/levelUp' => array(
		'controller' => 'storage',
		'action'     => 'levelUp',
		'page'		 => 'profile'
	),
    
    // удаление файла(в профиле)
	'smartstorage/profile/deleteFile' => array(
		'controller' => 'storage',
		'action'     => 'deleteFile',
		'page'		 => 'profile'
	),
    
    // удаление каталога(в профиле)
	'smartstorage/profile/deleteCatalog' => array(
		'controller' => 'storage',
		'action'     => 'deleteCatalog',
		'page'		 => 'profile'
	),
    
	//переход в другой каталог(в профиле)
	'smartstorage/profile/changeLocation' => array(
		'controller' => 'storage',
		'action'     => 'changeLocation',
		'page'		 => 'profile'
	),
    
    // скачивание файла(в профиле)
	'smartstorage/profile/downloadFile' => array(
		'controller' => 'storage',
		'action'     => 'downloadFile',
		'page'		 => 'profile'
	),     

	// страница просмотра пользовательских хранилищ
	'smartstorage/users' => array(
		'controller' => 'storage',
		'action'     => 'users',
		'page'		 => 'users'
	),      

	// переход на уровень вверх
	'smartstorage/users/levelUp' => array(
		'controller' => 'storage',
		'action'     => 'levelUp',
		'page'		 => 'users'
	),
	
	//переход в другой каталог
	'smartstorage/users/changeLocation' => array(
		'controller' => 'storage',
		'action'     => 'changeLocation',
		'page'		 => 'users'
	),
	
	// загрузка файла
	'smartstorage/users/downloadFile' => array(
		'controller' => 'storage',
		'action'     => 'downloadFile',
		'page'		 => 'users'
	),
    
	// удаление файла в чужом хранилище(для админа)
    'smartstorage/users/deleteFile' => array(
		'controller' => 'storage',
		'action'     => 'deleteFile',
		'page'		 => 'users'
	),
    
	// удаление каталога в чужом хранилище(для админа)
    'smartstorage/users/deleteCatalog' => array(
		'controller' => 'storage',
		'action'     => 'deleteCatalog',
		'page'		 => 'users'
	),
);