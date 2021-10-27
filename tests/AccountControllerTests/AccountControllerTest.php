<?php

use PHPUnit\Framework\TestCase;
use application\models\Account;
use  \application\controllers\AccountController;


spl_autoload_register(function($class) {
    $path = str_replace('\\', '/', $class.'.php');
    if (file_exists($path)) {
        require $path;
    }
});


class AccountControllerTest extends TestCase {
    
    /*
     * 
     * 
     * 
     * группа тестов на loginAction
     */
    //если правильные куки
    public function testAccountControllerLoginActionWithCorrectCookie()
    {      
        $controller = new AccountController(array('controller' => 'account',
            'action' 	 => 'login',
	));
        
        $_COOKIE['login']='gena@yandex.ru';
        $_COOKIE['key']='acb71dd620ead1b7d36e67c9ea0644f6c73b42e0007a7cd99146b5972bfe8bd8';

        $mockView = $this->getMockBuilder(View::class)
                         ->setMethods(['jumpOnPage'])
                         ->getMock();
         
        $controller->view = $mockView;
        
        $mockView->expects($this->once())->method('jumpOnPage')
        ->with('profile');
        
        $controller->loginAction();
    }
    
    //если неверное куки
    public function testAccountControllerLoginActionWithUncorrectCookie()
    {     
        $controller = new AccountController(array('controller' => 'account',
            'action' 	 => 'login',
	));

        $_COOKIE['login']='gna@yandex.ru';
        $_COOKIE['key']='acb71dd620ead1b7d36e67c9ea0644f6c73b42e0007a7cd99146b5972bfe8bd8';
              
        $mockView = $this->getMockBuilder(View::class)
                         ->setMethods(['generate'])
                         ->getMock();
         
        $controller->view = $mockView;
        $mockView->expects($this->once())->method('generate');
        
        $controller->loginAction();
       
    }
    
    //если нет куки и нет поста
    public function testAccountControllerLoginActionSignIn()
    {      
        $controller = new AccountController(array('controller' => 'account',
            'action' 	 => 'login',
	));
 
        $mockView = $this->getMockBuilder(View::class)
                         ->setMethods(['generate'])
                         ->getMock();
        $mockModel = $this->getMockBuilder(Model::class)
                         ->setMethods(['signIn','userLogged'])
                         ->getMock();
         
        $controller->view = $mockView;
        $controller->model = $mockModel;
        $mockModel->expects($this->once())->method('userLogged');
        
        $controller->loginAction();
    }
    
    //если передается верный пост и нет куки
    public function testAccountControllerLoginActionWithPostSuccess()
    {      
        $controller = new AccountController(array('controller' => 'account',
            'action' 	 => 'login',
	));
        $_POST['login']='gena@yandex.ru';
        unset($_COOKIE);
        $mockView = $this->getMockBuilder(View::class)
                         ->setMethods(['jumpOnPage'])
                        ->setMethods(['generate'])
                         ->getMock();
        $mockModel = $this->getMockBuilder(Model::class)
                         ->setMethods(['signIn','userLogged'])
                         ->getMock();
         
        $mockModel->method('signIn')
             ->willReturn('success');
        
        $controller->view = $mockView;
        $controller->model = $mockModel;
        $mockView->expects($this->once())->method('jumpOnPage')
    ->with('profile');
        
        $controller->loginAction();
    }
    
    //проверка ошибок при входе
    /**
     * @dataProvider dataProvider
     * 
     */
     
    public function testAccountControllerLoginActionWithPostError($arr) {
        $controller = new AccountController(array('controller' => 'account',
            'action' 	 => 'login',
	));
        
        $_POST['login']='kefir@rambler.an';

        
        $mockView = $this->getMockBuilder(View::class)
                         ->setMethods(['jumpOnPage'])
                        ->setMethods(['generate'])
                         ->getMock();
        $mockModel = $this->getMockBuilder(Model::class)
                         ->setMethods(['signIn','userLogged'])
                         ->getMock();

        $mockModel->method('signIn')
             ->willReturn($arr);
        
        $mockModel->method('userLogged')
             ->willReturn(false);
        
        $controller->view = $mockView;
        $controller->model = $mockModel;
        $mockView->expects($this->once())->method('generate')
    ->with(['error'=>$arr]);
        
        $controller->loginAction();
    }
    
    public function dataProvider(){
        return ['one'=>['error'=>'error: unvalid input data'],'two'=>['error'=>'error: user do not exists'],'three'=>['error'=>'error: invalid password']];
    }
    
    /*
     * 
     * 
     * 
     * группа тестов на logoutAction
     */
    
     public function testAccountControllerlogoutActionSignIn()
    {      
        $controller = new AccountController(array('controller' => 'account',
            'action' 	 => 'logout',
	));
 
        $mockView = $this->getMockBuilder(View::class)
                         ->setMethods(['jumpOnPage'])
                         ->getMock();
        $mockModel = $this->getMockBuilder(Model::class)
                         ->setMethods(['logout'])
                         ->getMock();
         
        $controller->view = $mockView;
        $controller->model = $mockModel;
        $mockModel->expects($this->once())->method('logout');
        $mockView->expects($this->once())->method('jumpOnPage')
         ->with('login');
        
        $controller->logoutAction();
    }
    
     /*
      * 
      * 
     * группа тестов на redisterAction
      * 
     */
    
    //если правильные куки
     public function testAccountControllerregisterActionWithCorrectCookie()
    {      
        $controller = new AccountController(array('controller' => 'account',
            'action' 	 => 'register',
	));
        
        $_COOKIE['login']='gena@yandex.ru';
        $_COOKIE['key']='acb71dd620ead1b7d36e67c9ea0644f6c73b42e0007a7cd99146b5972bfe8bd8';

        $mockView = $this->getMockBuilder(View::class)
                         ->setMethods(['jumpOnPage'])
                         ->getMock();
         
        $controller->view = $mockView;
        
        $mockView->expects($this->once())->method('jumpOnPage')
        ->with('profile');
        
        $controller->registerAction();
    }
    
    //если неверное куки
    public function testAccountControllerRegisterActionWithUncorrectCookie()
    {     
        $controller = new AccountController(array('controller' => 'account',
            'action' 	 => 'register',
	));

        $_COOKIE['login']='gna@yandex.ru';
        $_COOKIE['key']='acb71dd620ead1b7d36e67c9ea0644f6c73b42e0007a7cd99146b5972bfe8bd8';
              
        $mockView = $this->getMockBuilder(View::class)
                         ->setMethods(['generate'])
                         ->getMock();
         
        $controller->view = $mockView;
        $mockView->expects($this->once())->method('generate');
        
        $controller->registerAction();
       
    }
    
    //если нет куки и нет поста
    public function testAccountControllerRegisterActionSignUp()
    {      
        $controller = new AccountController(array('controller' => 'account',
            'action' 	 => 'register',
	));
 
        $mockView = $this->getMockBuilder(View::class)
                         ->setMethods(['generate'])
                         ->getMock();
        $mockModel = $this->getMockBuilder(Model::class)
                         ->setMethods(['signUp','userLogged'])
                         ->getMock();
         
        $controller->view = $mockView;
        $controller->model = $mockModel;
        $mockModel->expects($this->once())->method('userLogged');
        $mockView->expects($this->once())->method('generate');
        
        $controller->registerAction();
    }
    
    
    //если передается верный пост(для регистрации) и нет куки
    public function testAccountControllerRegisterActionWithPostSuccess()
    {      
        $controller = new AccountController(array('controller' => 'account',
            'action' 	 => 'register',
	));
        $_POST['login']='123';
        unset($_COOKIE);
        $mockView = $this->getMockBuilder(View::class)
                         ->setMethods(['jumpOnPage'])
                        ->setMethods(['generate'])
                         ->getMock();
        $mockModel = $this->getMockBuilder(Model::class)
                         ->setMethods(['signUp','userLogged'])
                         ->getMock();
         
        $mockModel->method('signUp')
             ->willReturn('success');
        
        $controller->view = $mockView;
        $controller->model = $mockModel;
        $mockView->expects($this->once())->method('jumpOnPage')
    ->with('profile');
        
        $controller->registerAction();
    }
    
    //проверка ошибок при регистрации
    /**
     * @dataProvider dataProviderTwo
     * 
     */
     
    public function testAccountControllerRegisterActionWithPostError($arr) {
        $controller = new AccountController(array('controller' => 'account',
            'action' 	 => 'register',
	));
        
        $_POST['login']='kefir@rambler.an';

        
        $mockView = $this->getMockBuilder(View::class)
                         ->setMethods(['jumpOnPage'])
                        ->setMethods(['generate'])
                         ->getMock();
        $mockModel = $this->getMockBuilder(Model::class)
                         ->setMethods(['signUp','userLogged'])
                         ->getMock();

        $mockModel->method('signUp')
             ->willReturn($arr);
        
        $mockModel->method('userLogged')
             ->willReturn(false);
        
        $controller->view = $mockView;
        $controller->model = $mockModel;
        $mockView->expects($this->once())->method('generate')
    ->with(['error'=>$arr]);
        
        $controller->registerAction();
    }
    
    public function dataProviderTwo(){
        return ['one'=>['error'=>'error: unvalid input data'],'two'=>['error'=>'error: user exists'],'three'=>['error'=>'error: insert fail']];
    }
}