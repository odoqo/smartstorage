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

    //добавление файла
    public function newFileAction()
    {
        $this->model->addFile();
        $this->view->redirect('http://localhost/smartstorage/profile/');
    }
        
    //добавление каталога
    public function newCatalogAction()
    {
        $this->model->addCatalog();
        $this->view->redirect('http://localhost/smartstorage/profile/');
    }
    
    //возвращение на уровень назад
    public function levelUpAction()
    {
            $this->model->levelUp();
            $this->view->redirect('http://localhost/smartstorage/profile/');
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