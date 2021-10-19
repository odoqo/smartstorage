<?php

namespace application\controllers;

use application\core\Controller;

class AccountController extends Controller {

	public function loginAction() 
	{
          // setcookie('login', '');
          // setcookie('cookie', '');

		//проверка на наличие куки
                if (isset($_COOKIE['login']) && isset($_COOKIE['cookie'])) {  
                    
                    $userData = $this->model->checkUserByCookie();
                    //для безопасности - проверка куки
                    if ($userData['cookie'] == $_COOKIE['cookie'] && $userData['login'] == $_COOKIE['login']) {
                       $this->view->redirect('http://localhost/smartstorage/profile/');
                    }
                    else {
                        $error = ['error' => 'error: other cookie'];
			$this->view->generate($error);
                    }
                }
		if (empty($_POST)) {
			$this->view->generate();
		} else {

			$status = $this->model->signIn();
			
			if ($status === 'success') {
				$this->view->redirect('http://localhost/smartstorage/profile/');
			}

			$error = ['error' => $status];
			$this->view->generate($error);
		}
	}
	
	public function registerAction() 
	{
		if (empty($_POST)) {
			$this->view->generate();
		} else {
			
			$status = $this->model->signUp();
			if ($status === 'success') {
				$this->view->redirect('http://localhost/smartstorage/login/');
			}	

			$error = ['error' => $status];
			$this->view->generate($error);
		}
	}	
}