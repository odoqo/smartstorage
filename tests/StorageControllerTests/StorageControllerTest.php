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
     * Тестирование всех маршрутов на проверку неавторизованных пользователей
     * 
     * @dataProvider actionInfoProvider 
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
     * Тестирование всех маршрутов на проверку авторизованных пользователей,
     * имеющих доступ к действию 
     * 
     * @dataProvider actionInfoProvider 
     */
    public function testWhenUserHasAccessToAction(
        $__route,
        $__checkAccess,
        $__methodThatCheckAccess,
        $__methodThatDoesAction,
        $__methodThatDisplay
    )
    {
        if ($__checkAccess === false) {
        
            $model = $this->getMockBuilder(Storage::class)
                          ->disableOriginalConstructor()
                          ->onlyMethods(['notAuthorized', $__methodThatDoesAction])
                          ->getMock();
        } else {

            $model = $this->getMockBuilder(Storage::class)
                          ->disableOriginalConstructor()
                          ->onlyMethods(['notAuthorized', $__methodThatDoesAction, $__methodThatCheckAccess])
                          ->getMock();

            $model->method($__methodThatCheckAccess)
                  ->willReturn(true);
        }

        $view = $this->getMockBuilder(View::class)
                     ->disableOriginalConstructor()
                     ->onlyMethods([$__methodThatDisplay])
                     ->getMock();

        $model->method('notAuthorized')
              ->willReturn(false);   
            
        $model->expects($this->once())
              ->method('notAuthorized');    

        $model->expects($this->once())
              ->method($__methodThatDoesAction);

        $view->expects($this->once())
             ->method($__methodThatDisplay);

        $action = $__route['action'] . 'Action';
        $storageController = new StorageController($__route);
        $storageController->model = $model; // замена на двойника
        $storageController->view  = $view;  // замена на двойника
        $storageController->$action();   
    }

    /**
     * Тестирование всех маршрутов на проверку авторизованных пользователей,
     * не имеющих доступ к действию.
     * 
     * 
     * @dataProvider actionRequiedTestError 
     */
    public function testWhenUserHasNotAccessToAction(
        $__route,
        $__methodThatCheckAccess
    )
    {
        $model = $this->getMockBuilder(Storage::class)
                        ->disableOriginalConstructor()
                        ->onlyMethods(['notAuthorized', $__methodThatCheckAccess])
                        ->getMock();

        $model->method('notAuthorized')
                ->willReturn(false);   

        $model->method($__methodThatCheckAccess)
                ->willReturn(false);

        $model->expects($this->once())
                ->method('notAuthorized');    

        $model->expects($this->once())
                ->method($__methodThatCheckAccess);

        $view = $this->getMockBuilder(View::class)
                ->disableOriginalConstructor()
                ->onlyMethods(['errorCode'])
                ->getMock();

        $view->expects($this->once())
            ->method('errorCode');

        $action = $__route['action'] . 'Action';
        $storageController = new StorageController($__route);
        $storageController->model = $model; // замена на двойника
        $storageController->view  = $view;  // замена на двойника
        $storageController->$action();
    }

    /**
     * Провйдер доставляет различных(но определенных) обработчиков для тестирования
     */
    public function actionRequiedTestError() {
       
        $routesInfoArr = require 'providers/actionsInfo.php';
        foreach ($routesInfoArr as $key => $route) {
         
            if ($route['checkAccess'] === false || $route['route']['action'] === 'profile') {
                unset($routesInfoArr[$key]);
            }

            unset($routesInfoArr[$key]['checkAccess']);
            unset($routesInfoArr[$key]['methodThatDoesAction']);
            unset($routesInfoArr[$key]['methodThatDisplay']);
        }
        return $routesInfoArr;
    }

    /**
     * Провйдер доставляет различных обработчиков для тестирования
     */
    public function actionInfoProvider()
    {
        return require 'providers/actionsInfo.php'; 
    }
}