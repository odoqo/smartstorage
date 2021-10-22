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
            $this->model->changePosition();
            $dataArr['list_users_cycle'] = $this->model->getUsersList();
            $dataArr['list_users_cycle1'] = $this->model->getUsersList();
            $dataArr['current_dir'] = $_COOKIE['login'];
            $this->view->generate($dataArr);
        } else {
            $this->view->redirect('http://localhost/smartstorage/login/');
        }
       
    }
    
    //добавление файла
    public function addfAction()
    {
        $this->model->addFile($_POST['file_rights'],$_POST['list_of_users']);
        $this->view->redirect('http://localhost/smartstorage/profile/');
    }
    
    //добавление каталога
    public function addcAction()
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
}
