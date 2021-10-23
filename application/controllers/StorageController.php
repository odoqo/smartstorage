<?php

namespace application\controllers;

use application\core\Controller;
use application\core\View;

class StorageController extends Controller 
{

    // хранилище пользователя
    public function profileAction()
    {    
        if (!$this->model->authorized()) {
            $this->view->redirect('http://localhost/smartstorage/login/'); 
        }    
        
        $dataArr = $this->model->getProfileData();
        $this->view->generate($dataArr);    
    }

    public function usersAction()
    {    
        if (!$this->model->authorized()) {
            $this->view->redirect('http://localhost/smartstorage/login/'); 
        }    
        
        if ($this->model->access()) {
            $dataArr = $this->model->getUsersData();
            $this->view->generate($dataArr);    
        } else {
            View::errorCode(403);
        }
    }


    // добавление файла
    public function newFileAction()
    {
        if (!$this->model->authorized()) {
            $this->view->redirect('http://localhost/smartstorage/login/'); 
        }

        $this->model->newFile();
        $this->view->redirect('http://localhost/smartstorage/profile/');
    }
    
    // добавление каталога
    public function newCatalogAction()
    {
        if (!$this->model->authorized()) {
            $this->view->redirect('http://localhost/smartstorage/login/'); 
        }

        $this->model->addCatalog();
        $this->view->redirect('http://localhost/smartstorage/profile/');
    }

    // удаление файла
    public function deleteFileAction()
    {  
        if (!$this->model->authorized()) {
            $this->view->redirect('http://localhost/smartstorage/login/'); 
        }

        if ($this->model->accessToDeleteFile()) {
            $this->model->deleteFile();
            $this->view->redirect('http://localhost/smartstorage/profile/');
    
        } else {
            View::errorCode(403);
        }
    }

    // удаление каталога
    public function deleteCatalogAction()
    {
        if (!$this->model->authorized()) {
            $this->view->redirect('http://localhost/smartstorage/login/'); 
        }

        if ($this->model->accessToDeleteCatalog()) {
            $this->model->deleteCatalog();
            $this->view->redirect('http://localhost/smartstorage/profile/');
    
        } else {
            View::errorCode(403);
        }
    }
    
    // загрузка файла
    public function downloadFileAction()
    {
        if (!$this->model->authorized()) {
            $this->view->redirect('http://localhost/smartstorage/login/'); 
        }

        if ($this->model->access()) {
            $this->model->downloadFile();    
            $this->view->redirect('http://localhost/smartstorage/profile/');
        
        } else {
            View::errorCode(403);
        }
    }

    // смена текущего каталога
    public function changeLocationAction()
    {
        if (!$this->model->authorized()) {
            $this->view->redirect('http://localhost/smartstorage/login/'); 
        }

        $this->model->changeLocation();
        $this->view->redirect('http://localhost/smartstorage/profile/');
    }

    // подъём на уровень вверх
    public function levelUpAction()
    {
        if (!$this->model->authorized()) {
            $this->view->redirect('http://localhost/smartstorage/login/'); 
        }

        $this->model->levelUp();
        $this->view->redirect('http://localhost/smartstorage/profile/');
    }
}