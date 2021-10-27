    // /**
    //  * Тестирование profileAction() на пользователя не имеющего доступ к странице
    //  */
    // public function testProfileActionWhenUserIsNotOwner()
    // {
    //     $route = array(
    //         'controller' => 'storage',
    //         'action'     => 'profile',
    //         'page'		 => 'profile'
    //     );

    //     $model = $this->getMockBuilder(Storage::class)
    //                   ->disableOriginalConstructor()
    //                   ->onlyMethods(['notAuthorized', 'isOwner', 'home'])
    //                   ->getMock();
                      
    //     $view = $this->getMockBuilder(View::class)
    //                  ->disableOriginalConstructor()
    //                  ->onlyMethods(['jumpOnPage'])
    //                  ->getMock();
 
    //     $model->method('notAuthorized')
    //           ->willReturn(false);
        
    //     $model->method('isOwner')
    //           ->willReturn(false);

    //     $model->expects($this->once())
    //           ->method('notAuthorized');

    //     $model->expects($this->once())
    //           ->method('isOwner');

    //     $model->expects($this->once())
    //           ->method('home');

    //     $view->expects($this->once())
    //          ->method('jumpOnPage')  
    //          ->with('profile');

    //     $storageController = new StorageController($route);
    //     $storageController->model = $model; // замена на двойникаы
    //     $storageController->view  = $view;  // замена на двойника
    //     $storageController->profileAction();
    // }

    // /**
    //  * Тестирование profileAction() на пользователя имеющего доступ к странице
    //  */
    // public function testProfileActionWhenUserIsOwner()
    // {
    //     $route = array(
    //         'controller' => 'storage',
    //         'action'     => 'profile',
    //         'page'		 => 'profile'
    //     );

    //     $model = $this->getMockBuilder(Storage::class)
    //                   ->disableOriginalConstructor()
    //                   ->onlyMethods(['notAuthorized', 'isOwner', 'getProfileData'])
    //                   ->getMock();
                      
    //     $view = $this->getMockBuilder(View::class)
    //                  ->disableOriginalConstructor()
    //                  ->onlyMethods(['generate'])
    //                  ->getMock();
 
    //     $model->method('notAuthorized')
    //           ->willReturn(false);
        
    //     $model->method('isOwner')
    //           ->willReturn(true);

    //     $model->expects($this->once())
    //           ->method('notAuthorized');

    //     $model->expects($this->once())
    //           ->method('isOwner');

    //     $model->expects($this->once())
    //           ->method('getProfileData');

    //     $view->expects($this->once())
    //          ->method('generate');

    //     $storageController = new StorageController($route);
    //     $storageController->model = $model; // замена на двойникаы
    //     $storageController->view  = $view;  // замена на двойника
    //     $storageController->profileAction();
    // }

    // /**
    //  * Тестирование usersAction() на авторизованного пользователя 
    //  */
    // public function testUsersActionWhenUserIsOwner()
    // {
    //     $route = array(
    //         'controller' => 'storage',
    //         'action'     => 'users',
    //         'page'		 => 'users'
    //     );

    //     $model = $this->getMockBuilder(Storage::class)
    //                   ->disableProxyingToOriginalMethods()
    //                   ->onlyMethods(['notAuthorized', 'getUsersData'])
    //                   ->getMock();
                      
    //     $view = $this->getMockBuilder(View::class)
    //                  ->disableOriginalConstructor()
    //                  ->disableProxyingToOriginalMethods()
    //                  ->onlyMethods(['generate'])
    //                  ->getMock();
 
    //     $model->method('notAuthorized')
    //           ->willReturn(false);  

    //     $model->expects($this->once())
    //           ->method('notAuthorized');

    //     $model->expects($this->once())
    //           ->method('getUsersData');

    //     $view->expects($this->once())
    //          ->method('generate');

    //     $storageController = new StorageController($route);
    //     $storageController->model = $model; // замена на двойникаы
    //     $storageController->view  = $view;  // замена на двойника
    //     $storageController->usersAction();
    // }

    // /**
    //  * Тестирование newFileAction() на пользователя имеющего доступ
    //  */
    // public function testNewFileActionWhenUserIsOwner()
    // {
    //     $route = array(
    //         'controller' => 'storage',
    //         'action'     => 'newFile',
    //         'page'		 => 'profile'
    //     );

    //     $model = $this->getMockBuilder(Storage::class)
    //                   ->disableOriginalConstructor()
    //                   ->onlyMethods(['notAuthorized', 'isOwner', 'newFile'])
    //                   ->getMock();
                      
    //     $view = $this->getMockBuilder(View::class)
    //                  ->disableOriginalConstructor()
    //                  ->onlyMethods(['jumpOnPage'])
    //                  ->getMock();
 
    //     $model->method('notAuthorized')
    //           ->willReturn(false);
        
    //     $model->method('isOwner')
    //           ->willReturn(true);

    //     $model->expects($this->once())
    //           ->method('notAuthorized');

    //     $model->expects($this->once())
    //           ->method('isOwner');

    //     $model->expects($this->once())
    //           ->method('newFile');

    //     $view->expects($this->once())
    //          ->method('jumpOnPage')
    //          ->with('profile');

    //     $storageController = new StorageController($route);
    //     $storageController->model = $model; // замена на двойникаы
    //     $storageController->view  = $view;  // замена на двойника
    //     $storageController->newFileAction();
    // }

    // /**
    //  * Тестирование newFileAction() на пользователя не имеющего доступ
    //  */
    // public function testNewFileActionWhenUserIsNotOwner()
    // {
    //     $route = array(
    //         'controller' => 'storage',
    //         'action'     => 'newFile',
    //         'page'		 => 'profile'
    //     );

    //     $model = $this->getMockBuilder(Storage::class)
    //                   ->disableOriginalConstructor()
    //                   ->onlyMethods(['notAuthorized', 'isOwner'])
    //                   ->getMock();
                      
    //     $view = $this->getMockBuilder(View::class)
    //                  ->disableOriginalConstructor()
    //                  ->onlyMethods(['errorCode'])
    //                  ->getMock();
 
    //     $model->method('notAuthorized')
    //           ->willReturn(false);
        
    //     $model->method('isOwner')
    //           ->willReturn(false);

    //     $model->expects($this->once())
    //           ->method('notAuthorized');

    //     $model->expects($this->once())
    //           ->method('isOwner');

    //     $view->expects($this->once())
    //          ->method('errorCode')
    //          ->with(403);

    //     $storageController = new StorageController($route);
    //     $storageController->model = $model; // замена на двойникаы
    //     $storageController->view  = $view;  // замена на двойника
    //     $storageController->newFileAction();
    // }

    // /**
    //  * Тестирование newCatalogAction() на пользователя имеющего доступ
    //  */
    // public function testNewCatalogActionWhenUserIsOwner()
    // {
    //     $route = array(
    //         'controller' => 'storage',
    //         'action'     => 'newCatalog',
    //         'page'		 => 'profile'
    //     );

    //     $model = $this->getMockBuilder(Storage::class)
    //                   ->disableOriginalConstructor()
    //                   ->onlyMethods(['notAuthorized', 'isOwner', 'newCatalog'])
    //                   ->getMock();
                      
    //     $view = $this->getMockBuilder(View::class)
    //                  ->disableOriginalConstructor()
    //                  ->onlyMethods(['jumpOnPage'])
    //                  ->getMock();
 
    //     $model->method('notAuthorized')
    //           ->willReturn(false);
        
    //     $model->method('isOwner')
    //           ->willReturn(true);

    //     $model->expects($this->once())
    //           ->method('notAuthorized');

    //     $model->expects($this->once())
    //           ->method('isOwner');

    //     $model->expects($this->once())
    //           ->method('newCatalog');

    //     $view->expects($this->once())
    //          ->method('jumpOnPage')
    //          ->with('profile');

    //     $storageController = new StorageController($route);
    //     $storageController->model = $model; // замена на двойникаы
    //     $storageController->view  = $view;  // замена на двойника
    //     $storageController->newCatalogAction();
    // }

    // /**
    //  * Тестирование newCatalogAction() на пользователя не имеющего доступ
    //  */
    // public function testNewCatalogActionWhenUserIsNotOwner($__route)
    // {
    //     $route = array(
    //         'controller' => 'storage',
    //         'action'     => 'newCatalog',
    //         'page'		 => 'profile'
    //     );

    //     $model = $this->getMockBuilder(Storage::class)
    //                   ->disableOriginalConstructor()
    //                   ->onlyMethods(['notAuthorized', 'isOwner'])
    //                   ->getMock();
                      
    //     $view = $this->getMockBuilder(View::class)
    //                  ->disableOriginalConstructor()
    //                  ->onlyMethods(['errorCode'])
    //                  ->getMock();
 
    //     $model->method('notAuthorized')
    //           ->willReturn(false);
        
    //     $model->method('isOwner')
    //           ->willReturn(true);

    //     $model->expects($this->once())
    //           ->method('notAuthorized');

    //     $model->expects($this->once())
    //           ->method('isOwner');

    //     $model->expects($this->once())
    //           ->method('newCatalog');

    //     $view->expects($this->once())
    //          ->method('jumpOnPage')
    //          ->with('profile');

    //     $storageController = new StorageController($route);
    //     $storageController->model = $model; // замена на двойникаы
    //     $storageController->view  = $view;  // замена на двойника
    //     $storageController->newCatalogAction();
    // }
