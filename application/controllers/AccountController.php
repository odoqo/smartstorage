<?php

namespace application\controllers;

use application\core\Controller;

class AccountController extends Controller {

	public function loginAction() 
	{
		//echo 'login page';
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