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

    public function getProfileData()
    {
        $dataArray['data_cycle'] = $this->getLocalData();
        $dataArray['location']   = $this->location();
        return $dataArray;
    }

    public function getUsersData()
    {
        $dataArray = $this->getProfileData();
        $dataArray['users_cycle'] = $this->getUsersList();
        return $dataArray;
    }

    private function getLocalData()
    {
        $location = $this->location();
        
        $info = ['id', 'name', 'owner', 'rights'];
        
        $files     = $this->db->selectRows('files', By::vPath($location), $info);
        $cataloges = $this->db->selectRows('cataloges', By::vPath($location), $info);

        return $this->formatingData($files, $cataloges);
    }

    private function getUsersList()
    {
        $login = $_COOKIE['login'];
        $users = $this->db->selectRows('users', By::all(), ['login']);
        
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

    private function location() 
    {
        return $_SESSION['location'] ?? $_COOKIE['login'];
    }
}