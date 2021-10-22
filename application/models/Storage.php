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
            $login = $_COOKIE['login'];
            $key   = $_COOKIE['key'];
            return $this->db->selectRow('users', By::loginAndCookie($login, $key));
        }

        return false;
    }
    
    //добавление файла
    public function addFile($right, $users) 
    {
        $this->uploadFile($_SESSION['position'], $_FILES['file'], $right);
        $data = $_SESSION['position'].'/'.$_FILES['file']['name'].'[|||]';
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
            $this->newCatalog($name, $_SESSION['position'].'/'.$name, $right);
            $data=$_SESSION['position'].'/'.$name.'[|||]';
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
            'right'        => $right
        );

        $this->db->insertRow('cataloges', $data);
    }
    
    //смена текущего положения на следующее
    public function changePosition($newPos='') 
    {     
        if(!isset($_SESSION['position'])) {
            $_SESSION['position'] = $this->getName();
        } elseif($newPos != '') {
            $_SESSION['position'] = $_SESSION['position'].'/'.$newPos;
        }
    }
    
    //смена текущего положения на предыдущее
    public function backPosition($newPos='') 
    {
        if($_SESSION['position']!=$user->getName()){
            $position             = strrpos($_SESSION['position'], '/');
            $_SESSION['position'] = substr($_SESSION['position'],0, $position);
        }
    }
    
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
            'right'        => $right,
        );
        
        $result = $this->db->insertRow('files', $data);
        
        if (!$result) {
            return 'error: dataBase';
        }

        return 1;
    }

    private function getName()
    {
        return $_COOKIE['login'];
    }

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
	
}