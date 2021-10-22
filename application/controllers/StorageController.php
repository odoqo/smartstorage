<?php

namespace application\controllers;

use application\core\Controller;

class StorageController extends Controller 
{
    public function profileAction()
    {        
        if ($this->model->authorized()) {
            $dataArr = $this->model->getProfileData();
            $this->view->generate($dataArr);
        } else {
            $this->view->redirect('http://localhost/smartstorage/login/');
        }
    }

    public function userAction()
    {
        
    }

    public function usersAction()
    {
        if ($this->model->authorized()) {
            $dataArr = $this->model->getUsersData();
            $this->view->generate($dataArr);
        } else {
            $this->view->redirect('http://localhost/smartstorage/login/');
        }   
    }

}