<?php

namespace application\controllers;

use application\core\Controller;

class StorageController extends Controller 
{
    
    public function profileAction()
    {
       
        if ($this->model->authorized()) {
            $this->model->changePosition();
            
            //if (isset($_POST['addf'])) {
              //  var_dump($_POST);
             //   exit;
              //  $this->model->addFile($_FILES,$_POST['flexRadioDefault'],$_POST['list_of_users']);
            //} 
            //$dataArr['list_users_cycle'] = $this->model->getUsersList();
           // $this->view->generate($this->model->getDataArr);
            $dataArr['list_users_cycle'] = $this->model->getUsersList();
            $dataArr['list_users_cycle1'] = $this->model->getUsersList();
            $dataArr['current_dir'] = $_COOKIE['login'];
            $this->view->generate($dataArr);
        } else {
            $this->view->redirect('http://localhost/smartstorage/login/');
        }
       
    }
    
    public function addfAction()
    {
            $this->model->addFile($_POST['file_rights'],$_POST['list_of_users']);
            $this->view->redirect('http://localhost/smartstorage/profile/');
    }
    
    public function addcAction()
    {
            $this->model->addCatalog($_POST['catalog'],$_POST['catalog_rights'],$_POST['list_of_users']);
            $this->view->redirect('http://localhost/smartstorage/profile/');
    }
    
        public function backAction()
    {
            $this->model->addCatalog($_POST['catalog'],$_POST['catalog_rights'],$_POST['list_of_users']);
            $this->view->redirect('http://localhost/smartstorage/profile/');
    }

}