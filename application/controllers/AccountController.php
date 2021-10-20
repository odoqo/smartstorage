<?php

namespace application\controllers;

use application\core\Controller;

class AccountController extends Controller {

	public function loginAction() 
	{
		// проверка на авторизированного пользователя
		if ($this->model->userLogged()) {
			//$this->view->redirect('http://localhost/smartstorage/profile/');
		} 
		
		// проверка на наличие данных для входа
		if (empty($_POST)) {
			$this->view->generate();
		} 
		
		// вход в аккаунт
		else {

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
		// проверка на авторизированного пользователя
		if ($this->model->userLogged()) {
			//$this->view->redirect('http://localhost/smartstorage/profile/');
		} 
		
		// проверка на наличие данных для регистрации
		if (empty($_POST)) {
			$this->view->generate();
		} 
		
		// регистрация аккаунта
		else {
			
			$status = $this->model->signUp();
			if ($status === 'success') {
				$this->view->redirect('http://localhost/smartstorage/profile/');
			}	

			$error = ['error' => $status];
			$this->view->generate($error);
		}	
	}

	public function logoutAction()
	{
		$this->model->logout();
		$this->view->redirect('http://localhost/smartstorage/login/');
	}

}