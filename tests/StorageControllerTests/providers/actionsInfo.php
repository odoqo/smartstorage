<?php

return array(

    // профиль пользователя
    [
        'route' => [
            'controller' => 'storage',
            'action'     => 'profile',
            'page'		 => 'profile'
        ],
        
        'checkAccess'           => true,
        'methodThatCheckAccess' => 'isOwner',
        'methodThatDoesAction'  => 'getProfileData',
        'methodThatDisplay'     => 'generate',
    ],
    
	// страница просмотра пользовательских хранилищ
    [
        'page' => [
            'controller' => 'storage',
            'action'     => 'users',
            'page'		 => 'users'
        ],
            
        'checkAccess'           => false,
        'methodThatCheckAccess' => '',
        'methodThatDoesAction'  => 'getUsersData',
        'methodThatDisplay'     => 'generate',
    ],      

	// возвращение в свое хранилище
	[
        'route' => [
            'controller' => 'storage',
            'action' 	 => 'home',
        ],

        'checkAccess'           => false,
        'methodThatCheckAccess' => '',
        'methodThatDoesAction'  => 'home',
        'methodThatDisplay'     => 'jumpOnPage',
    ],

	// добавление файла
	[
        'route' => [
            'controller' => 'storage',
            'action'     => 'newFile',
            'page'		 => 'profile'
        ],

        'checkAccess'           => true,
        'methodThatCheckAccess' => 'isOwner',
        'methodThatDoesAction'  => 'newFile',
        'methodThatDisplay'     => 'jumpOnPage',
	],
    
	// добавление каталога
	[
        'route' => [
            'controller' => 'storage',
            'action'     => 'newCatalog',
            'page'		 => 'profile'
        ],

        'checkAccess'           => true,
        'methodThatCheckAccess' => 'isOwner',
        'methodThatDoesAction'  => 'newCatalog',
        'methodThatDisplay'     => 'jumpOnPage',
    ],
    
    // переход на уровень вверх(в профиле)
	[
        'route' => [
            'controller' => 'storage',
            'action'     => 'levelUp',
            'page'		 => 'profile'
        ],
        
        'checkAccess'           => false,
        'methodThatCheckAccess' => '',
        'methodThatDoesAction'  => 'levelUp',
        'methodThatDisplay'     => 'jumpOnPage',
    ],

    // переход на уровень вверх
	[
	    'route' => [
            'controller' => 'storage',
		    'action'     => 'levelUp',
		    'page'		 => 'users'
        ],

        'checkAccess'           => false,
        'methodThatCheckAccess' => '',
        'methodThatDoesAction'  => 'levelUp',
        'methodThatDisplay'     => 'jumpOnPage',
    ],
    
    // удаление файла(в профиле)
	[
        'route' => [
            'controller' => 'storage',
            'action'     => 'deleteFile',
            'page'		 => 'profile'
        ],
        
        'checkAccess'           => true,
        'methodThatCheckAccess' => 'availableToDelete',
        'methodThatDoesAction'  => 'deleteFile',
        'methodThatDisplay'     => 'jumpOnPage',
    ],

    // удаление чужого файла
	[
        'route' => [
            'controller' => 'storage',
            'action'     => 'deleteFile',
            'page'		 => 'users'
        ],
        
        'checkAccess'           => true,
        'methodThatCheckAccess' => 'availableToDelete',
        'methodThatDoesAction'  => 'deleteFile',
        'methodThatDisplay'     => 'jumpOnPage',
    ],

    // удаление каталога(в профиле)   
    [
        'route' => [
            'controller' => 'storage',
            'action'     => 'deleteCatalog',
            'page'		 => 'profile'
        ],
        
        'checkAccess'           => true,
        'methodThatCheckAccess' => 'availableToDelete',
        'methodThatDoesAction'  => 'deleteCatalog',
        'methodThatDisplay'     => 'jumpOnPage',
    ],

    // удаление каталога в чужом хранилище(для админа)
    [
        'route' => [
            'controller' => 'storage',
            'action'     => 'deleteCatalog',
            'page'		 => 'users'
        ],
        
        'checkAccess'           => true,
        'methodThatCheckAccess' => 'availableToDelete',
        'methodThatDoesAction'  => 'deleteCatalog',
        'methodThatDisplay'     => 'jumpOnPage',
    ],
    
	//переход в другой каталог(в профиле)
	[
        'route' => [
            'controller' => 'storage',
            'action'     => 'changeLocation',
            'page'		 => 'profile'
        ],
        
        'checkAccess'           => true,
        'methodThatCheckAccess' => 'availableForViewing',
        'methodThatDoesAction'  => 'changeLocation',
        'methodThatDisplay'     => 'jumpOnPage',
    ],

    //переход в другой каталог(в профиле)
	[
        'route' => [
            'controller' => 'storage',
            'action'     => 'changeLocation',
            'page'		 => 'users'
        ],
        
        'checkAccess'           => true,
        'methodThatCheckAccess' => 'availableForViewing',
        'methodThatDoesAction'  => 'changeLocation',
        'methodThatDisplay'     => 'jumpOnPage',
    ],
    
    // скачивание файла(в профиле)
	[
	    'route' => [
            'controller' => 'storage',
		    'action'     => 'downloadFile',
		    'page'		 => 'profile'
        ],
        
        'checkAccess'           => true,
        'methodThatCheckAccess' => 'availableToDownload',
        'methodThatDoesAction'  => 'downloadFile',
        'methodThatDisplay'     => 'jumpOnPage',
    ],

    // скачивание чужого файла
	[
	    'route' => [
            'controller' => 'storage',
		    'action'     => 'downloadFile',
		    'page'		 => 'users'
        ],
        
        'checkAccess'           => true,
        'methodThatCheckAccess' => 'availableToDownload',
        'methodThatDoesAction'  => 'downloadFile',
        'methodThatDisplay'     => 'jumpOnPage',
    ],
);