<?php

namespace application\models;

use application\core\Model;
use application\lib\By;
use application\lib\Db;
use application\lib\CSV;
use Exception;

class Storage extends Model {

    private $location;
    private $login;

    public function __construct()
    {
        parent::__construct();
        $this->location = $_SESSION['location'] ?? $_COOKIE['login'] ?? false;
        $this->login    = $_COOKIE['login'] ?? false;
    }

    public function authorized()
    {
        if (isset($_SESSION['auth']) && $_SESSION['auth'] === true) {
            return true;
        }

        if (isset($_COOKIE['login']) && isset($_COOKIE['key'])) {
            $login = $_COOKIE['login'];
            $key   = $_COOKIE['key'];
            //$_SESSION['location'] = $this->location;
            return $this->db->selectRow('users', By::loginAndCookie($login, $key));
        }

        return false;
    }
    
    public function accessToDeleteFile()
    {
        $fileId = $_POST['delete_file'] ?? '';
        return $this->db->selectRow('files', By::idAndOwner($fileId, $this->login));
    }

    public function accessToDeleteCatalog()
    {
        $catalogId = $_POST['delete_catalog'] ?? '';
        return $this->db->selectRow('cataloges', By::idAndOwner($catalogId, $this->login));
    }

    public function getProfileData()
    {
        $dataArray['cataloges_cycle']  = $this->getLocalCataloges();
        $dataArray['files_cycle']      = $this->getLocalFiles();      
        $dataArray['list_users_cycle'] = $this->getUsersList();
        $dataArray['location']         = $this->location;    
        return $dataArray;
    }


    // создание нового файла
    public function newFile() 
    {
        $file = $this->uploadFile();

        $right = $_POST['file_rights'] ?? 'private';
   
        if($right === 'protected') {
            $this->writeAccessList($file['name']);      
        }       
    }

    // загрузка файла на сервер
    private function uploadFile()
    {
        $tmpName = $_FILES['file']['tmp_name'] ?? ''; 
        $name    = $_FILES['file']['name'] ?? '';
        $path    = 'application/data/'. time() . $name;
    
        if(!move_uploaded_file($tmpName, $path)) {
            throw new Exception('uploaded failed');
        }

        $data = array(
            'name'         => $name,
            'path'         => $path,
            'virtual_path' => $this->location,
            'owner'        => $this->login,
            'rights'       => $_POST['file_rights'] ?? 'private',
        );
        
        $result = $this->db->insertRow('files', $data);
        
        if (!$result) {
            throw new Exception('insert failed');
        }

        return $data;
    }

    // создание нового каталога
    public function newCatalog() 
    {
        $cataloges = $this->addCatalog();

        if($cataloges['right'] === 'protected') {
            $this->writeAccessList($cataloges['name']);      
        }                 
    }
    
    // добавление каталога в базу
    public function addCatalog()
    {
        $cataloges = array(
            'name'         => $_POST['catalog'] ?? 'Новая папка',
            'virtual_path' => $this->location,
            'owner'        => $this->login,
            'rights'       => $_POST['file_rights'] ?? 'private',
        );

        $this->db->insertRow('cataloges', $cataloges);
        return $cataloges;
    }
    
    // переход в укзанную директорию
    public function changeLocation() 
    {     
        $catalog_id = $_POST['go'] ?? '';
        $catalog    = $this->db->selectRow('cataloges', By::id($catalog_id), ['name']);       
        $_SESSION['location'] .= '/' . $catalog['name'];
    }
    
    // подъем на один уровень вверх
    public function levelUp() 
    {
        if ($this->location !== $this->login) {

            $token = strrpos($this->location, '/');
            $this->location = substr($this->location, 0, $token);
        }

        $_SESSION['location'] = $this->location;
    }
    
    // удаление файла
    public function deleteFile()
    {       
        $id = $_POST['delete_file'] ?? '';
        $this->db->deleteRow('files', By::id($id));
    }
    
    // удаление каталога
     public function deleteCatalog()
    {  
        $id = $_POST['delete_file'] ?? '';
        $this->db->deleteRow('cataloges', By::id($id));
    }

    // массив файлов в текущем расположении
    private function getLocalFiles()
    {
        $location = $this->location;
        
        $info = ['id', 'name', 'owner', 'rights'];
        
        //debug($this->db);
        
        $files = $this->db->selectRows('files', By::vPath($location), $info);
     
        return $this->formatingData($files);
    }

    // массив каталогов в текущем расположении
    private function getLocalCataloges()
    {
        $location = $this->location;
        
        $info = ['id', 'name', 'owner', 'rights'];

        
       // debug($this->db);
        $cataloges = $this->db->selectRows('cataloges', By::vPath($location), $info);

        return $this->formatingData($cataloges);
    }

    // список всех пользователей
    private function getUsersList()
    {
        $users = $this->db->selectRows('users', By::all());
        
        if (!$users) {
            return [];
        }
    
        foreach ($users as $number => $user) {
            $users[$number]['user'] = $user[1];
        }

        return $users;
    }	


    private function formatingData(array $__data)
    {
        foreach ($__data as $key => $fragment) {
            $__data[$key]['id_for_action'] = $fragment[0];
            $__data[$key]['id_for_delete'] = $fragment[0];    
            $__data[$key]['name']          = $fragment[1];
            $__data[$key]['rights']        = $fragment[3];
        }
        return $__data;
    }

    private function writeAccessList($__filename)
    {
        $usersList = $_POST['users_list'] ?? []; 
        $accessRow = $this->location . '/' . $__filename . '[|||]';
        
        foreach ($usersList as $user){
            $accessRow .= $user . '[|]';
        }

        file_put_contents('application\\rights\\rights.txt', PHP_EOL . $accessRow, FILE_APPEND | LOCK_EX);
    }
}