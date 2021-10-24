<?php

namespace application\models;

use application\core\Model;
use application\lib\By;
use application\lib\CSV;
use Exception;

class Storage extends Model {

    private $location;
    private $login;
    private $csv;

    public function __construct()
    {
        parent::__construct();
        $this->login    = $_SESSION['login'] ?? $_COOKIE['login'] ?? false;
        $this->location = $_SESSION['location'] ?? $this->login;
        $this->csv      = new CSV('application\rights\rights.csv');
    }

    /**
     * Проверка на пользователя на авторизированность
     * 
     * @return bool
     */
    public function notAuthorized()
    {
        if (isset($_SESSION['auth']) && $_SESSION['auth'] === true) {
            return false;
        }

        if (isset($_COOKIE['login']) && isset($_COOKIE['key'])) {
            $login = $_COOKIE['login'];
            $key   = $_COOKIE['key'];
            return !$this->db->selectRow('users', By::loginAndCookie($login, $key));
        }

        return true;
    }

    /**
     * Смена локации в свое хранилище 
     */
    public function home()
    {
        $_SESSION['location'] = $this->login;
    }

    /**
     * Проверка на владельца каталога
     * 
     * @return bool
     */
    public function isOwner()
    {
        return preg_match("/(^$this->login$)|(^$this->login\/)/", $this->location);
    }

    /**
     * Проверка на возможность удаления данных
     * 
     * @return bool
     */
    public function availableToDelete()
    {
        if (isset($_POST['delete_file'])) {
            $fileId = $_POST['delete_file'];
            return $this->db->selectRow('files', By::idAndOwner($fileId, $this->login));
        }

        if (isset($_POST['delete_catalog'])) {
            $catalogId = $_POST['delete_catalog'] ?? '';
            return $this->db->selectRow('cataloges', By::idAndOwner($catalogId, $this->login));                
        }

        return false;
    }

    /**
     * Проверка на возможность загрузки
     * 
     * @return bool
     */
    public function availableToDownload()
    {
        $fileId = $_POST['download'] ?? '';
        $file   = $this->db->selectRow('files', By::id($fileId));
        
        $isOwnerFile   = $file['owner'] == $this->login;
        $isPublicFile  = $file['rights'] == 'public';
        $fileAvailable = in_array($this->login, $this->csv->readRow($file['virtual_path']));
        
        return $isOwnerFile || $isPublicFile || $fileAvailable;
    }

    /**
     * Проверка на возможность просмотра каталога
     * 
     * @return bool
     */
    public function availableForViewing()
    {
        // для перехода в хранилище выбранного пользователя
        if (!empty($_POST['user'])) {
            return true; // смотреть можно любое хранилище
        }

        // для смены каталога в текущем хранилище
        elseif (!empty($_POST['go'])) {
            $catalogId = $_POST['go'];
            $catalog   = $this->db->selectRow('cataloges', By::id($catalogId));
        
            $isOwnerCatalog   = $catalog['owner'] === $this->login;
            $isPublicCatalog  = $catalog['rights'] === 'public';
            $catalogAvailable = in_array($this->login, $this->csv->readRow($catalog['virtual_path']));

            return  $isOwnerCatalog || $isPublicCatalog || $catalogAvailable;
        }

        return false;
    }

    /**
     * Смена текущего расположения
     */
    public function changeLocation() 
    {
        // переход в хранилище выбранного пользователя
        if (!empty($_POST['user'])) {
            $_SESSION['location'] = $_POST['user'];
        }

        // смена каталога в текущем хранилище
        elseif (!empty($_POST['go'])) {
            $catalogId = $_POST['go'];
            $catalog   = $this->db->selectRow('cataloges', By::id($catalogId));
            $_SESSION['location'] = $catalog['virtual_path'];
        }
    }

    /**
     * Формирование массива данных для шаблонизатора
     * 
     * @return array
     */
    public function getProfileData()
    {
        $dataArray['cataloges_cycle']   = $this->getLocalCataloges();
        $dataArray['files_cycle']       = $this->getLocalFiles();      
        $dataArray['flist_users_cycle'] = $dataArray['clist_users_cycle'] = $this->getUsersList();
        $dataArray['location']          = $this->location;    
        return $dataArray;
    }

    /**
     * 
     * 
     * @return array
     */
    public function getUsersData()
    {
        $dataArray['cataloges_cycle'] = $this->getLocalCataloges();
        $dataArray['files_cycle']     = $this->getLocalFiles();      
        $dataArray['users_cycle']     = $this->getUsersList(); 
        $dataArray['location']        = $this->location;    
        return $dataArray;
    }


    /**
     * Загрузка файла на сервер и добавление информации о нем в базу
     */
    public function newFile() 
    {
        $file = $this->addFile();
        
        // добавление списка пользователей имеющих доступ к файлу
        if($file['rights'] === 'protected') {
            $accessList = empty($_POST['access_list']) ? [] : $_POST['access_list'];
            array_unshift($accessList, $file['virtual_path']);
            $this->csv->writeRow($accessList);      
        }       
            
        // выбор каталога, путь до которого - текущее расположение
        // т.е каталог в который добавляется файл  
        $catalog = $this->db->selectRow('cataloges', By::virtualPath($this->location));

        // смена прав каталога в соответствии с правами добаленного файла    
        if ($catalog) {
            $this->changeRights($file, $catalog);
        }
    }

    /**
     * Смена прав на каталог в соответсвии с добавляемым файлом
     * 
     * @param array $__file    Информация о файле.
     * @param array $__catalog Информация о каталоге, права которго будут изменены.
     */
    private function changeRights(array $__file, array $__catalog)
    {
        if ($__file['rights'] == 'public' || $__file['rights'] == 'private') {
            $__catalog['rights'] = $__file['rights'];
            $this->csv->deleteRow($__catalog['virtual_path']);
        }

        if ($__file['rights'] == 'protected') {
            $__catalog['rights'] = 'protected';
            $this->csv->deleteRow($__catalog['virtual_path']);
     
            // новый список доступа к файлу
            $accessList    = $this->csv->readRow($__file['virtual_path']);
            $accessList[0] = $__catalog['virtual_path']; 
            $this->csv->writeRow($accessList);
        }

        $this->db->updateFields('cataloges', By::id($__catalog['id']), $__catalog);        
    }

    /**
     * Загрузка файла на сервер и добавление информации в базу
     * 
     * @return array Информация о добавленном файле
     */
    private function addFile()
    {
        $tmpName = $_FILES['file']['tmp_name'] ?? ''; 
        $name    = $_FILES['file']['name'] ?? '';
        $path    = 'application/data/'. time() . $name;
    
        if(!move_uploaded_file($tmpName, $path)) {
            throw new Exception('failed to upload file');
        }

        $rights   = empty($_POST['file_rights']) ? 'private' : $_POST['file_rights'];
        $uniqName = $this->uniqName($name);

        $data = array(
            'name'         => $uniqName,
            'path'         => $path,
            'virtual_path' => $this->location . '/' . $uniqName,
            'location'     => $this->location,
            'owner'        => $this->login,
            'rights'       => $rights,
        );
        
        $result = $this->db->insertRow('files', $data);
        
        if (!$result) {
            throw new Exception('failed to insert to the db');
        }

        return $data;
    }

    /**
     * Создание нового каталога
     */
    public function newCatalog() 
    {
        $catalog = $this->addCatalog();
        if ($catalog['rights'] === 'protected') {
            $accessList = empty($_POST['access_list']) ? [] : $_POST['access_list'];
            array_unshift($accessList, $catalog['virtual_path']);
            $this->csv->writeRow($accessList);       
        }                 
    }

    /**
     * Возвращает уникальное имя для добавляемых данных с названием $__name
     * 
     * @param  string $__name
     * @return string 
     */
    private function uniqName(string $__name)
    {
        // поиск файлов с таким же назаванием
        $catalog = $this->db->selectRow('cataloges', By::nameAndLocation($__name, $this->location));
        $file    = $this->db->selectRow('files', By::nameAndLocation($__name, $this->location));
        
        if ($catalog || $file) {
            return time() . $__name;
        }

        return $__name;
    }

    /**
     * Добавление каталога в базу
     * 
     * @return array Информация о добавленном каталоге
     */
    private function addCatalog()
    {
        $name   = empty($_POST['catalog']) ? 'Новая папка' : $_POST['catalog'];
        $rights = empty($_POST['catalog_rights']) ? 'private' : $_POST['catalog_rights'];
        
        $uniqName = $this->uniqName($name);

        $cataloges = array(
            'name'         => $uniqName,
            'location'     => $this->location,
            'virtual_path' => $this->location . '/' . $uniqName, 
            'owner'        => $this->login,
            'rights'       => $rights,
        );

        $this->db->insertRow('cataloges', $cataloges);
        return $cataloges;
    }
        
    /**
     * Удаление файла
     */
    public function deleteFile()
    {       
        $id = empty($_POST['delete_file']) ? '' : $_POST['delete_file'];

        $file = $this->db->selectRow('files', By::id($id));
        
        unlink($file['path']);
        $this->db->deleteRow('files', By::id($id));
        $this->csv->deleteRow($file['virtual_path']);
    }

    /**
     * Удаление каталога
     */
    public function deleteCatalog()
    {  
        $id = empty($_POST['delete_catalog']) ? '' : $_POST['delete_catalog']; 

        $catalog = $this->db->selectRow('cataloges', By::id($id), ['virtual_path']);
        
        // проверка на непустой каталог
        $catalogNotEmpty = $this->db->selectRow('files', By::location($catalog['virtual_path']));
        if ($catalogNotEmpty) {
            throw new Exception('catalog is not empty');
        }

        $this->db->deleteRow('cataloges', By::id($id));
    }
    
    /**
     * Смена текущего расположения на один уровень вверх (подъем к корню)
     */
    public function levelUp() 
    {
        $token = strrpos($this->location, '/');
        if ($token !== false) {
            $this->location = substr($this->location, 0, $token);
        }

        $_SESSION['location'] = $this->location;
    }
    
    /**
     * Загрузка файла с сервера
     */
    public function downloadFile() 
    {
        $id   = empty($_POST['download']) ? '' : $_POST['download'];
        $file = $this->db->selectRow('files', By::id($id)); 
        $path = $file['path'];

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
        }
    }

    /**
     * Генерация массива файлов текущего расположения
     * 
     * @return array Массив файлов текущего расположения.
     */
    private function getLocalFiles()
    {
        $location = $this->location;
        
        $info = ['id', 'name', 'rights'];
        
        $files = $this->db->selectRows('files', By::location($location), $info);
     
        return $this->formatingData($files);
    }

     /**
     * Генерация массива каталогов текущего расположения
     * 
     * @return array Массив каталогов текущего расположения.
     */
    private function getLocalCataloges()
    {
        $location = $this->location;
        
        $info = ['id', 'name', 'rights'];

        $cataloges = $this->db->selectRows('cataloges', By::location($location), $info);

        return $this->formatingData($cataloges);
    }

    /**
     * Генерация массива(цикла) пользователей для шаблонизатора
     * 
     * @return array Массив пользователей.
     */
    private function getUsersList()
    {
        $users = $this->db->selectRows('users', By::all());
        
        if (!$users) {
            return [];
        }
    
        foreach ($users as $number => $user) {
            $users[$number]['user_id'] = $user[0];
            $users[$number]['user']    = $user[1];
        }

        return $users;
    }

    /**
     * Форматирование данных текущего расположения для шаблонизатора
     * 
     * @param array Массив данных.
     *  
     * @return array Отформатированный массив.
     */
    private function formatingData(array $__data)
    {
        foreach ($__data as $key => $fragment) {
            $__data[$key]['id_for_action'] = $fragment[0]; // id
            $__data[$key]['id_for_delete'] = $fragment[0]; // id
            $__data[$key]['name']          = $fragment[1]; // name
            $__data[$key]['rights']        = $fragment[2]; // rights
        }
        return $__data;
    }
}