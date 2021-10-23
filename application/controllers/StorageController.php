<?php

//контроллер основной страницы smartstorage
namespace application\controllers;

use application\core\Controller;

class StorageController extends Controller 
{
    //основная страница-свое хранилище
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
        $this->model->addFile($_POST['file_rights'],$_POST['list_of_users']);
        $this->view->redirect('http://localhost/smartstorage/profile/');
    }
    
    //добавление каталога
    public function newCatalogAction()
    {
        $this->model->addCatalog($_POST['catalog'],$_POST['catalog_rights'],$_POST['list_of_users']);
        $this->view->redirect('http://localhost/smartstorage/profile/');
    }
    
    //возвращение на уровень назад
    public function backAction()
    {
        $this->model->backPosition();
        $this->view->redirect('http://localhost/smartstorage/profile/');
    }
    
    //удаление файла
    public function deleteFileAction()
    {
        $this->model->deleteFile($_POST['delete']);
        $this->view->redirect('http://localhost/smartstorage/profile/');
    }
    
    //удаление каталога
    public function deleteCatalogAction()
    {
        $this->model->deleteCatalog($_POST['delete']);
        $this->view->redirect('http://localhost/smartstorage/profile/');
    }
    
     //удаление каталога
    public function changeCatalogAction()
    {
        $this->model->changeLocation($_POST['go']);
        $this->view->redirect('http://localhost/smartstorage/profile/');
    }
    
    //загрузка файла
    public function downloadFileAction()
    {
        $this->model->downloadFile($_POST['download']);
        $this->view->redirect('http://localhost/smartstorage/profile/');
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
