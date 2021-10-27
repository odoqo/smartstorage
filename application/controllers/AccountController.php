<?php

namespace application\controllers;

use application\core\Controller;

/**
 * Контроллер учетных записей пользователей
 */
class AccountController extends Controller 
{

	/**
	 * Обработчик входа в аккаунт 
	 */
	public function loginAction() 
	{
		// проверка на уже вошедшего пользователя
		if ($this->model->userLogged()) {
			$this->view->jumpOnPage('profile');
		}
		
		// проверка на наличие данных для входа
		elseif (empty($_POST)) {
			$this->view->generate();
		} 
		
		// вход в аккаунт
		else {

			$status = $this->model->signIn();
			
			if ($status === 'success') {
				$this->view->jumpOnPage('profile');
			}

			$error = ['error' => $status];
			$this->view->generate($error);
		}
	}

	/**
	 * Обработчик регистрации аккаунта 
	 */
	public function registerAction() 
	{
		// проверка на вошедшего пользователя
		if ($this->model->userLogged()) {
			$this->view->jumpOnPage('profile');
		} 
		
		// проверка на наличие данных для регистрации
		elseif (empty($_POST)) {
			$this->view->generate();
		} 
		
		// регистрация аккаунта
		else {
			
			$status = $this->model->signUp();
			if ($status === 'success') {
				$this->view->jumpOnPage('profile');
			}	

			$error = ['error' => $status];
			$this->view->generate($error);
		}	
	}

	/**
	 * Обработчик выхода из аккаунта 
	 */
	public function logoutAction()
	{
		$this->model->logout();
		$this->view->jumpOnPage('login');
	}
}