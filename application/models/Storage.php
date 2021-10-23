<?php

//модель основной страницы - smartstorage

namespace application\models;

use application\lib\Db;
use application\core\Model;
use application\lib\By;

class Storage extends Model {
    
    //проверка на авторизованность пользователя
    public function authorized()
    {
        if (isset($_SESSION['auth']) && $_SESSION === true) {
            return true;
        }

        if (isset($_COOKIE['login']) && isset($_COOKIE['key'])) {
            if(!isset($_SESSION['location'])){
                $_SESSION['location'] = $_COOKIE['login'];
            }
            $login = $_COOKIE['login'];
            $key   = $_COOKIE['key'];
            return $this->db->selectRow('users', By::loginAndCookie($login, $key));
        }

        return false;
    }
    
    //добавление файла
    public function addFile($right, $users) 
    {
        $this->uploadFile($_SESSION['location'], $_FILES['file'], $right);
        $data = $_SESSION['location'].'/'.$_FILES['file']['name'].'[|||]';
        if($right == 'protected'){
            foreach ($users as $nameOfUser){
                $data .= $nameOfUser.'[|]';
            }
            file_put_contents('application\rightOfUsers/right.txt', PHP_EOL .$data, FILE_APPEND | LOCK_EX);
        }       
    }
    
    //добавление католога
    public function addCatalog($name,$right, $users) 
    {
        if($name != ''){
            $this->newCatalog($name, $_SESSION['location'], $right);
            $data=$_SESSION['location'].'/'.$name.'[|||]';
             if($right == 'protected'){
                foreach ($users as $nameOfUser){
                    $data .= $nameOfUser.'[|]';
                }
                file_put_contents('application\rightOfUsers/right.txt', PHP_EOL .$data, FILE_APPEND | LOCK_EX);
            }
        }          
    }
    
    //добавление в базу каталога
    public function newCatalog(string $__name, string $__virtualPath, string $right)
    {
        $data = array(
            'name'         => $__name,
            'owner'        => $this->getName(),
            'virtual_path' => $__virtualPath,
            'rights'        => $right
        );

        $this->db->insertRow('cataloges', $data);
    }
    
    //смена текущего положения на следующее
    public function changeLocation(string $__id) 
    { 
        $data=$this->db->selectRow('cataloges', By::id($__id));       
        $_SESSION['location'] = $data['virtual_path'].'/'.$data['name'];
    }
    
    //смена текущего положения на предыдущее
    public function backPosition(string $newPos='') 
    {
        if($_SESSION['location']!=$this->getName()){
            $position             = strrpos($_SESSION['location'], '/');
            $_SESSION['location'] = substr($_SESSION['location'],0, $position);
        }
    }
    
    //загрузка файла
    public function downloadFile(string $__id) 
    {
         $path = $this->db->selectRow('files', By::id($__id))['path'];

        if (file_exists($path)) {
            
            if (ob_get_level()) {
                ob_end_clean();
            }

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($path));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($path));
            
            readfile($path);
            exit;
        }
    }
    
    //добавление файла
     public function uploadFile(string $__virtualPath, array $__upload, string $right)
    {
        $tmpName = $__upload['tmp_name']; 
        $name    = $__upload['name'];
        $path    = 'application/FILES/'. time() . $name;
    
        if(!move_uploaded_file($tmpName, $path)) {
            return 'error: upload file';
        }

        $data = array(
            'name'         => $name,
            'path'         => $path,
            'virtual_path' => $__virtualPath,
            'owner'        => $this->getName(),
            'rights'        => $right,
        );
        
        $result = $this->db->insertRow('files', $data);
        
        if (!$result) {
            return 'error: dataBase';
        }

        return 1;
    }

    //для просмотра имени
    private function getName()
    {
        return $_COOKIE['login'];
    }

    //вывод списка всех пользователей
    public function getUsersList()
    {
        $login = $_COOKIE['login'];
        $users = $this->db->selectRows('users', By::notLogin($login), ['login']);
        
        if (!$users) {
            return [];
        }
    
        foreach ($users as $number => $user) {
            $users[$number]['user'] = $user[0];
        }

        return $users;
    } 
    
    //удаление файла
     public function deleteFile(string $__id)
    {       
        $this->db->deleteRow('files', By::id($__id));
    }
    
        //удаление файла
     public function deleteCatalog(string $__id)
    {  
        $this->db->deleteRow('cataloges', By::id($__id));
    }
    
    public function getProfileData()
    {
        $dataArray['files_cycle'] = $this->getLocalData('files');
        $dataArray['cataloges_cycle'] = $this->getLocalData('cataloges');      
        $dataArray['location']   = $this->location();    
        return $dataArray;
    }

    public function getUsersData()
    {
        $dataArray = $this->getProfileData();
        $dataArray['users_cycle'] = $this->getUsersList();
        return $dataArray;
    }

    private function getLocalData($fileOrCat)
    {
        $location = $this->location();
        
        $info = ['id', 'name', 'owner', 'rights'];
        
        $data     = $this->db->selectRows($fileOrCat, By::vPath($location), $info);

        return $this->formatingData($data, $fileOrCat);
    }

    private function formatingData(array $__data, string $__fileOrCat)
    {
        $formatingData=array();
        if($__fileOrCat=='files') {
            foreach ($__data as $key => $file) {

                $formatingData[$key]['id_for_action']      = $file[0];
                $formatingData[$key]['id_for_delete']      = $file[0]; 
                $formatingData[$key]['name']               = $file[1];
            }
        }
        else{
            foreach ($__data as $key => $catalog) {
                $formatingData[$key]['id_for_action']  = $catalog[0];
                $formatingData[$key]['id_for_delete']  = $catalog[0];
                $formatingData[$key]['name']           = $catalog[1];    
            }
        }
        return $formatingData;
    }

    private function location() 
    {
        return $_SESSION['location'];
    }
	
}