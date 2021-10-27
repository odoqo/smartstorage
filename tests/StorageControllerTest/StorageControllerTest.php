<?php	

use application\controllers\StorageController;
use application\core\View;
use \PHPUnit\Framework\TestCase;
use application\models\Storage;

spl_autoload_register(function($class) {
    $path = str_replace('\\', '/', $class.'.php');
    if (file_exists($path)) {
        require $path;
    }

});

class StorageControllerTest extends TestCase
{    

    /**
     * @dataProvider routesProvider 
     */
    public function testWhenUserDoNotAuthorized($__route)
    {
        $model = $this->getMockBuilder(Storage::class)
                      ->disableOriginalConstructor()
                      ->onlyMethods(['notAuthorized'])
                      ->getMock();
                      
        $view = $this->getMockBuilder(View::class)
                    ->disableOriginalConstructor()
                     ->onlyMethods(['jumpOnPage'])
                     ->getMock();
 
        $model->method('notAuthorized')
              ->willReturn(true);   
        
        $model->expects($this->once())
              ->method('notAuthorized');
      
        $view->expects($this->once())
             ->method('jumpOnPage')  
             ->with('login');

        $storageController = new StorageController($__route);
        $storageController->model = $model; // замена на двойникаы
        $storageController->view  = $view;  // замена на двойника
        $storageController->profileAction();
    }

    /**
     * @dataProvider routesProvider
     */
    public function testWhenUserHasAccessToAction($__route, $__modelMethods, $__viewMethods)
    {
        $model = $this->getMockBuilder(Storage::class)
                      ->disableOriginalConstructor()
                      ->onlyMethods($__modelMethods)
                      ->getMock();
                      
        $view = $this->getMockBuilder(View::class)
                    ->disableOriginalConstructor()
                     ->onlyMethods($__viewMethods)
                     ->getMock();
 
        $methodThatDoesAction  = $__modelMethods['methodThatDoesAction'];
        $methodThatCheckAccess = $__modelMethods['methodThatCheckAccess'];
        $methodDisplayOnScreen = $__viewMethods[0];

        // пользователь авторизован
        $model->method('notAuthorized')
              ->willReturn(false);   
      
        $model->method($methodThatCheckAccess)
              ->willReturn(true);
        
        $model->expects($this->once())
              ->method($methodThatCheckAccess);
        

        $model->expects($this->once())
              ->method($methodThatDoesAction);
        
        $model->expects($this->once())
              ->method('notAuthorized');

        if ($methodDisplayOnScreen === 'generate') {
            $view->expects($this->once())
                 ->method($methodDisplayOnScreen);
        } elseif ($methodDisplayOnScreen === 'jumpOnPage') {
            $view->expects($this->once())
                 ->method($methodDisplayOnScreen)
                 ->with($__route['page']);
        }
        
        $storageController = new StorageController($__route);
        $storageController->model = $model; // замена на двойникаы
        $storageController->view  = $view;  // замена на двойника
        $storageController->profileAction();        
    }



    public function routesProvider()
    {
        return [
            [   
                // профиль пользователя
                'route' => array(
                    'controller' => 'storage',
                    'action'     => 'profile',
                    'page'		 => 'profile'
                ),

                'modelMetods' => array(
                    'notAuthorized',
                    'methodThatCheckAccess' => 'isOwner',
                    'methodThatDoesAction'  => 'getProfileData'
                ),  

                'viewMetods' => array(
                    'generate'
                ),
            ],
            [
                // возвращение в свое хранилище
                'route' => array(
                    'controller' => 'storage',
                    'action' 	 => 'home',
                ),

                'modelMetods' => array(
                    'notAuthorized',
                    'methodThatCheckAccess' => '',
                    'methodThatDoesAction'  => 'home'
                ),  

                'viewMetods' => array(
                    'jumpOnPage'
                ),
            ],
            [
                // добавление файла
                'route' => array(
                    'controller' => 'storage',
                    'action'     => 'newFile',
                    'page'		 => 'profile'
                ),
                    
                'modelMetods' => array(
                    'notAuthorized',
                    'methodThatCheckAccess' => 'isOwner',
                    'methodThatDoesAction'  => 'newFile'
                ),  

                'viewMetods' => array(
                    'jumpOnPage'
                ),
            ],
            [    
                // добавление каталога
                'route' => array(
                    'controller' => 'storage',
                    'action'     => 'newCatalog',
                    'page'		 => 'profile'
                ),
                                
                'modelMetods' => array(
                    'notAuthorized',
                    'methodThatCheckAccess' => 'isOwner',
                    'methodThatDoesAction'  => 'newCatalog'
                ),  

                'viewMetods' => array(
                    'jumpOnPage'
                ),
            ],
            [    
                // переход на уровень вверх(в профиле)
                'route' => array(
                    'controller' => 'storage',
                    'action'     => 'levelUp',
                    'page'		 => 'profile'
                ),
                                
                'modelMetods' => array(
                    'notAuthorized',
                    'methodThatCheckAccess' => '',
                    'methodThatDoesAction'  => 'leveUp'
                ),  

                'viewMetods' => array(
                    'jumpOnPage'
                ),
            ],
            [    
                // удаление файла(в профиле)
                'route' => array(
                    'controller' => 'storage',
                    'action'     => 'deleteFile',
                    'page'		 => 'profile'
                ),
                                
                'modelMetods' => array(
                    'notAuthorized',
                    'methodThatCheckAccess' => 'availableToDelete',
                    'methodThatDoesAction'  => 'deleteFile'
                ),  

                'viewMetods' => array(
                    'jumpOnPage'
                ),
            ],
            [    
                // удаление каталога(в профиле)
                'route' => array(
                    'controller' => 'storage',
                    'action'     => 'deleteCatalog',
                    'page'		 => 'profile'
                ),
                
                'modelMetods' => array(
                    'notAuthorized',
                    'methodThatCheckAccess' => 'availableToDelete',
                    'methodThatDoesAction'  => 'deleteCatalog'
                ),  

                'viewMetods' => array(
                    'jumpOnPage'
                ),
            ],    
            [
                //переход в другой каталог(в профиле)
                'route' => array(
                    'controller' => 'storage',
                    'action'     => 'changeLocation',
                    'page'		 => 'profile'
                ),
                
                'modelMetods' => array(
                    'notAuthorized',
                    'methodThatCheckAccess' => 'availableForViewing',
                    'methodThatDoesAction'  => 'changeLocation'
                ),  

                'viewMetods' => array(
                    'jumpOnPage'
                ),
            ],
            [    
                // скачивание файла(в профиле)
                'route' => array(
                    'controller' => 'storage',
                    'action'     => 'downloadFile',
                    'page'		 => 'profile'
                ),   
                
                'modelMetods' => array(
                    'notAuthorized',
                    'methodThatCheckAccess' => 'availableToDownload',
                    'methodThatDoesAction'  => 'downloadFile'
                ),  

                'viewMetods' => array(
                    'jumpOnPage'
                ),
            ],
            [
                // страница просмотра пользовательских хранилищ
                'route' => array(
                    'controller' => 'storage',
                    'action'     => 'users',
                    'page'		 => 'users'
                ),      
                
                'modelMetods' => array(
                    'notAuthorized',
                    'methodThatCheckAccess' => '',
                    'methodThatDoesAction'  => 'getUsersData'
                ),  

                'viewMetods' => array(
                    'generate'
                ),
            ],
            [
                // переход на уровень вверх
                'route' => array(
                    'controller' => 'storage',
                    'action'     => 'levelUp',
                    'page'		 => 'users'
                ),
                
                'modelMetods' => array(
                    'notAuthorized',
                    'methodThatCheckAccess' => '',
                    'methodThatDoesAction'  => 'leveUp'
                ),  

                'viewMetods' => array(
                    'jumpOnPage'
                ),
            ],
            [    
                //переход в другой каталог
                'route' => array(
                    'controller' => 'storage',
                    'action'     => 'changeLocation',
                    'page'		 => 'users'
                ),
                
                'modelMetods' => array(
                    'notAuthorized',
                    'methodThatCheckAccess' => 'availableForViewing',
                    'methodThatDoesAction'  => 'changeLocation'
                ),  

                'viewMetods' => array(
                    'jumpOnPage'
                ),
            ],
            [    
                // загрузка файла
                'route' => array(
                    'controller' => 'storage',
                    'action'     => 'downloadFile',
                    'page'		 => 'users'
                ),
                
                'modelMetods' => array(
                    'notAuthorized',
                    'methodThatCheckAccess' => 'availableToDownload',
                    'methodThatDoesAction'  => 'downloadFile'
                ),  

                'viewMetods' => array(
                    'jumpOnPage'
                ),
            ],
            [    
                // удаление файла в чужом хранилище(для админа)
                'route' => array(
                    'controller' => 'storage',
                    'action'     => 'deleteFile',
                    'page'		 => 'users'
                ),
                
                'modelMetods' => array(
                    'notAuthorized',
                    'methodThatCheckAccess' => 'availableToDelete',
                    'methodThatDoesAction'  => 'deleteFile'
                ),  

                'viewMetods' => array(
                    'jumpOnPage'
                ),
            ],
            [    
                // удаление каталога в чужом хранилище(для админа)
                'route' => array(
                    'controller' => 'storage',
                    'action'     => 'deleteCatalog',
                    'page'		 => 'users'
                ),
                
                'modelMetods' => array(
                    'notAuthorized',
                    'methodThatCheckAccess' => 'availableToDelete',
                    'methodThatDoesAction'  => 'deleteCatalog'
                ),  

                'viewMetods' => array(
                    'jumpOnPage'
                ),
            ],
        ];
    }
}