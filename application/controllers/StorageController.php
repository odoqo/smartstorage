<?php

namespace application\controllers;

use application\core\Controller;

class StorageController extends Controller 
{
    public function profileAction()
    {        
        if ($this->model->authorized()) {
            //$dataArr = $this->model->getDataArr();
            $this->view->generate();//$dataArr);
        } else {
            $this->view->redirect('http://localhost/smartstorage/login/');
        }
    }

}