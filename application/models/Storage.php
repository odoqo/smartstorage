<?php

namespace application\models;

use application\core\Model;
use application\lib\By;
use application\lib\Db;

class Storage extends Model {

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




    public function getDataArr()
    {
        $usersCycle = ['users_cycle' => $this->getUsersList()];
        

    }

    private function getDataCurrentDir()
    {
        $login      = $_COOKIE['login'];
        $currentDir = $_SESSION['currentDir'] ?? $_COOKIE['login'];
        
        $fileInfo    = ['id', 'name', 'path', 'rights'];
        $catalogInfo = ['id', 'name', 'rights'];
        
        
        $filesCurDir     = $this->db->selectRows('files', By::vPathAndOwner($currentDir, $login), $fileInfo);
        $catalogesCurDir = $this->db->selectRows('files', By::vPathAndOwner($currentDir, $login), $catalogInfo);
             
        

    }

    private function getUsersList()
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