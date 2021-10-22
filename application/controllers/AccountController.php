<?php

namespace application\controllers;

use application\core\Controller;

class AccountController extends Controller {

    //действие для входа
    public function loginAction() 
    {
            // проверка на авторизированного пользователя
            if ($this->model->userLogged()) {
                    $this->view->redirect('http://localhost/smartstorage/profile/');
            } 

            // проверка на наличие данных для входа
            elseif (empty($_POST)) {
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

    //действие для регистрации
    public function registerAction() 
    {
            // проверка на авторизированного пользователя
            if ($this->model->userLogged()) {
                    $this->view->redirect('http://localhost/smartstorage/profile/');
            } 

            // проверка на наличие данных для регистрации
            elseif (empty($_POST)) {
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

    //выход с основной страницы
    public function logoutAction()
    {
            $this->model->logout();
            $this->view->redirect('http://localhost/smartstorage/');
    }
}
