<?php

namespace application\controllers;

use application\core\Controller;
use application\core\View;

/**
 * Контроллер основных действий на сайте
 */
class StorageController extends Controller 
{
    /**
     * Главная страница пользователя
     */
    public function profileAction()
    {    
        // 1 этап авторизации
        if ($this->model->notAuthorized()) {
            $this->view->jumpOnPage('login'); 
        }    
        
        // 2 этап авторизации
        elseif ($this->model->isOwner()) {
            $dataArr = $this->model->getProfileData();
            $this->view->generate($dataArr);    
        } 
        
        // враг не пройдет (наверно). Точнее пройдет только к себе в аккаунт
        else {
            $this->model->home();
            $this->view->jumpOnPage('profile');
        }
    }

    /**
     * Главная страница просмотра пользовательких хранилищ
     */
    public function usersAction()
    {    
        // авторизация
        if ($this->model->notAuthorized()) {
            $this->view->jumpOnPage('login'); 
        }
        
        $dataArr = $this->model->getUsersData();
        $this->view->generate($dataArr);        
    }

    /**
     * Обработчик добавления файла 
     */
    public function newFileAction()
    {   
        // 1 этап автоизации
        if ($this->model->notAuthorized()) {
            $this->view->jumpOnPage('login');
        }

        // 2 этап авторизации
        elseif ($this->model->isOwner()) {    
            $this->model->newFile();
            $this->view->jumpOnPage('profile');
        } 
        
        // враг не пройдет
        else {
            $this->view->errorCode(403);
        }
    }
    
    /**
     * Обработчик добавления каталога
     */
    public function newCatalogAction()
    {   
        // 1 этап автоизации
        if ($this->model->notAuthorized()) {
            $this->view->jumpOnPage('login');
        }

        // 2 этап авторизации
        elseif ($this->model->isOwner()) {    
            $this->model->newCatalog();
            $this->view->jumpOnPage('profile');
        } 
        
        // враг не пройдет
        else {
            $this->view->errorCode(403);
        }
    }

     /**
     * Обработчик удаления файла
     */
    public function deleteFileAction()
    {  
        // 1 этап авторизации
        if ($this->model->notAuthorized()) {
            $this->view->jumpOnPage('login');
        }

        // 2 этап авторизации
        elseif ($this->model->availableToDelete()) {
            $this->model->deleteFile();
            $this->view->jumpOnPage('profile');
        } 
        
        // враг не пройдет
        else {
            $this->view->errorCode(403);
        }
    }

    /**
     * Обработчик удаления каталога
     */
    public function deleteCatalogAction()
    {
        // 1 этап авторизции
        if ($this->model->notAuthorized()) {
            $this->view->jumpOnPage('login');
        }

        // 2 этап авторизции
        elseif ($this->model->availableToDelete()) {
            $this->model->deleteCatalog();
            $this->view->jumpOnPage('profile');  
        } 

        //враг не пройдёт
        else {
            $this->view->errorCode(403);
        }
    }
    
    /**
     * Обработчик загрузки файла
     */
    public function downloadFileAction()
    {
        // 1 этап авторизации
        if ($this->model->notAuthorized()) {
            $this->view->jumpOnPage('login'); 
        }

        // 2 этап авторизации
        elseif ($this->model->availableToDownload()) {
            $this->model->downloadFile();    
            $page = $this->route['page'];
            $this->view->jumpOnPage($page);
        } 

        // враг не пройдёт
        else {
            $this->view->errorCode(403);
        }
    }
    
    /**
     * Обработчик возвращения в свое хранилище
     */
    public function homeAction()
    {
        // авторизация
        if ($this->model->notAuthorized()) {
            $this->view->jumpOnPage('login');    
        }

        $this->model->home();
        $this->view->jumpOnPage('profile');
    }

    /**
     * Обработчик смены просматриваемого каталога
     */
    public function changeLocationAction()
    {
        // 1 этап авторизации
        if ($this->model->notAuthorized()) {
            $this->view->jumpOnPage('login'); 
        }

        // 2 этап авторизации
        elseif ($this->model->availableForViewing()) {
            $this->model->changeLocation();
            $page = $this->route['page'];
            $this->view->jumpOnPage($page);    
        }

        // враг не пройдет
        else {
            $this->view->errorCode(403);
        }
    }

     /**
     * Обработчик смены просматриваемого каталога на один уровень выше
     */
    public function levelUpAction()
    {
        // авторизация
        if ($this->model->notAuthorized()) {
            $this->view->jumpOnPage('login'); 
        }

        $this->model->levelUp();
        $page = $this->route['page'];
        $this->view->jumpOnPage($page);
    }
}