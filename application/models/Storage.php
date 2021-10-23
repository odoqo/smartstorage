<?php

namespace application\models;

use application\core\Model;
use application\lib\By;
use application\lib\Db;
use application\lib\CSV;

class Storage extends Model {

    private $location;
    private $login;

    public function __construct()
    {
        $this->location = $_SESSION['location'] ?? $_COOKIE['login'] ?? false;
        $this->login    = $_COOKIE['login'] ?? false;
    }

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

    public function getProfileData()
    {
        $dataArray['data_cycle'] = $this->getLocalData();
        $dataArray['location']   = $this->location;
        return $dataArray;
    }

    public function getUsersData()
    {
        $dataArray = $this->getProfileData();
        $dataArray['users_cycle'] = $this->getUsersList();
        return $dataArray;
    }

    //добавление файла
    public function newFile() 
    {
        $this->addFile();

        $right = $_SESSION['file_rights'];

        if($right == 'protected') {
        
            $csvManager = new CSV("application\\rights\\accessList.csv");
            $users = $_POST['list_of_users'];
            $vPath = $_SESSION['location'].'/'.$_FILES['file']['name'];

            array_unshift($users, $vPath);
            
            $csvManager->setCSV($users);
        }       
    }

    
    //добавление католога
    public function newCatalog() 
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
    public function addCatalog()
    {
        $data = array(
            'name'         => $_SESSION['catalog'],
            'owner'        => $this->login,
            'virtual_path' => $this->location,
            'right'        => $right
        );

        $this->db->insertRow('cataloges', $data);
    }
    
    //смена текущего положения на следующее
    public function changeLocation() 
    {     
        if(!isset($_SESSION[''])) {
            $_SESSION['position'] = $this->login;
        } elseif($newPos != '') {
            $_SESSION['position'] = $_SESSION['position'].'/'.$newPos;
        }
    }
    
    //смена текущего положения на предыдущее
    public function levelUp() 
    {
        $pos = strrpos($_SESSION['position'], '/');

        $_SESSION['location'] = $this->location == $this->login 
            ? $_SESSION['location'] : substr($_SESSION['position'], 0, $pos);
    }
    
    private function addFile()
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
            'virtual_path' => $this->location,
            'owner'        => $this->login,
            'right'        => $_SESSION['rights'],
        );
        
        $result = $this->db->insertRow('files', $data);
        
        if (!$result) {
            return 'error: insert';
        }

        return 'success';
    }

    private function getLocalData()
    {

        $location = $this->location;
        
        $info = ['id', 'name', 'owner', 'rights'];
        
        $files     = $this->db->selectRows('files', By::vPath($location), $info);
        $cataloges = $this->db->selectRows('cataloges', By::vPath($location), $info);

        return $this->formatingData($files, $cataloges);
    }

    private function getUsersList()
    {
        $users = $this->db->selectRows('users', By::all());
        
        if (!$users) {
            return [];
        }
    
        foreach ($users as $number => $user) {
            $users[$number]['user'] = $user[0];
        }

        return $users;
    }	

    private function formatingData(array $__files, array $__cataloges)
    {
        foreach ($__files as $key => $file) {

            if ($file[3] === 'private') {
                unset($__files[$key]);
                continue;
            }

            $__files[$key]['view_action']   = 'Загрузить';
            $__files[$key]['name_action']   = 'download';
            $__files[$key]['id_for_action'] = $file[0];
            $__files[$key]['id_for_delete'] = $file[0];    
            $__files[$key]['name']          = $file[1];
        }

        foreach ($__cataloges as $key => $catalog) {

            if ($catalog[3] === 'private') {
                unset($__cataloges[$key]);
                continue;
            }

            $__cataloges[$key]['view_action']   = 'Перейти';
            $__cataloges[$key]['name_action']   = 'go';
            $__cataloges[$key]['id_for_action'] = $catalog[0];
            $__cataloges[$key]['id_for_delete'] = $catalog[0];
            $__cataloges[$key]['name']          = $catalog[1];    
        }

        return array_merge($__cataloges, $__files);
    }
}