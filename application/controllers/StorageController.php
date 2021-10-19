<?php

namespace application\controllers;

use application\core\Controller;

class StorageController extends Controller 
{
    public function profileAction()
    {
        $this->view->generate();
    }

}