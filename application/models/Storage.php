<?php

namespace application\models;

use application\core\Model;
use application\lib\SearchBy;
use application\lib\CSV;
use Exception;

/**
 * Модель содержит основную логику облачного хранилища
 */
class Storage extends Model
{
    private $location;
    private $login;
    private $csv;
    private $isAdmin;

    /**
     * Конструктор
     */
    public function __construct()
    {
        parent::__construct();
        $this->login    = $_SESSION['login'] ?? $_COOKIE['login'] ?? '';
        $this->location = $_SESSION['location'] ?? $this->login;
        $this->csv      = new CSV('application\rights\rights.csv');
        $user = $this->db->selectRow('users', SearchBy::login($this->login));
        $this->isAdmin  = $user['isAdmin'] ?? false;
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
            return empty($this->db->selectRow('users', SearchBy::loginAndCookie($login, $key)));
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
        if ($this->isAdmin) {
            return true;
        }
        
        if (isset($_POST['delete_file'])) {
            $fileId = $_POST['delete_file'];
            return $this->db->selectRow('files', SearchBy::idAndOwner($fileId, $this->login));
        }

        if (isset($_POST['delete_catalog'])) {
            $catalogId = $_POST['delete_catalog'] ?? '';
            return $this->db->selectRow('cataloges', SearchBy::idAndOwner($catalogId, $this->login));                
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
        if ($this->isAdmin) {
            return true;
        }

        $fileId = $_POST['download'] ?? '';
        $file   = $this->db->selectRow('files', SearchBy::id($fileId));
        
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
        if ($this->isAdmin) {
            return true;
        }

        // для перехода в хранилище выбранного пользователя
        if (!empty($_POST['user'])) {
            return true; // смотреть можно любое хранилище
        } elseif (!empty($_POST['go'])) {  // для смены каталога в текущем хранилище
            $catalogId = $_POST['go'];
            $catalog   = $this->db->selectRow('cataloges', SearchBy::id($catalogId));
        
            $isOwnerCatalog   = $catalog['owner'] === $this->login;
            $isPublicCatalog  = $catalog['rights'] === 'public';
            $catalogAvailable = in_array($this->login, $this->csv->readRow($catalog['virtual_path']));

            return  $isOwnerCatalog || $isPublicCatalog || $catalogAvailable;
        }

        return false;
    }

    /**
     * Смена текущего расположения
     * 
     * @return void
     */
    public function changeLocation() 
    {
        // переход в хранилище выбранного пользователя
        if (!empty($_POST['user'])) {
            $_SESSION['location'] = $_POST['user'];
        } elseif (!empty($_POST['go'])) { // смена каталога в текущем хранилище
            $catalogId = $_POST['go'];
            $catalog   = $this->db->selectRow('cataloges', SearchBy::id($catalogId));
            $_SESSION['location'] = $catalog['virtual_path'];
        }
    }

    /**
     * Формирование массива данных для шаблонизатора страницы профиля
     * 
     * @return array
     */
    public function getProfileData()
    {
        $dataArray['cataloges_cycle']   = $this->_getLocalCataloges();
        $dataArray['files_cycle']       = $this->_getLocalFiles();      
        $dataArray['flist_users_cycle'] = $dataArray['clist_users_cycle'] = $this->_getUsersList();
        $dataArray['location']          = $this->location;    
        return $dataArray;
    }

    /**
     * Формирование массива данных для шаблонизатора страницы просмотра
     * пользователей
     * 
     * @return array
     */
    public function getUsersData()
    {
        $dataArray['cataloges_cycle'] = $this->_getLocalCataloges();
        $dataArray['isAdmin']         = $this->isAdmin;
        $dataArray['files_cycle']     = $this->_getLocalFiles();      
        $dataArray['users_cycle']     = $this->_getUsersList(); 
        $dataArray['location']        = $this->location;    
        return $dataArray;
    }


    /**
     * Загрузка файла на сервер и добавление информации о нем в базу
     * 
     * @return void
     */
    public function newFile() 
    {
        $file = $this->_addFile();
        
        // добавление списка пользователей имеющих доступ к файлу
        if ($file['rights'] === 'protected') {
            $accessList = empty($_POST['access_list']) ? [] : $_POST['access_list'];
            array_unshift($accessList, $file['virtual_path']);
            $this->csv->writeRow($accessList);      
        }       
            
        // выбор каталога, путь до которого - текущее расположение
        // т.е каталог в который добавляется файл  
        $catalog = $this->db->selectRow('cataloges', SearchBy::virtualPath($this->location));

        // смена прав каталога в соответствии с правами добаленного файла    
        if (!empty($catalog)) {
            $this->_changeRights($file, $catalog);
        }
    }

    /**
     * Смена прав на каталог в соответсвии с добавляемым файлом
     * 
     * @param array $__file    Информация о файле.
     * @param array $__catalog Информация о каталоге, права которго будут изменены.
     * 
     * @return void
     */
    private function _changeRights(array $__file, array $__catalog)
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

        $status = $this->db->updateFields('cataloges', SearchBy::id($__catalog['id']), $__catalog);

        if ($status === false) {
            throw new Exception('failed to update catalog rights');
        }        
    }

    /**
     * Загрузка файла на сервер и добавление информации в базу
     * 
     * @return array Информация о добавленном файле
     */
    private function _addFile()
    {
        $tmpName = $_FILES['file']['tmp_name'] ?? ''; 
        $name    = $_FILES['file']['name'] ?? '';
        $path    = 'application/data/'. time() . $name;
    
        if (move_uploaded_file($tmpName, $path) === false) {
            throw new Exception('failed to upload file');
        }

        $rights   = empty($_POST['file_rights']) ? 'private' : $_POST['file_rights'];
        $_uniqName = $this->_uniqName($name);

        $data = array(
            'name'         => $_uniqName,
            'path'         => $path,
            'virtual_path' => $this->location . '/' . $_uniqName,
            'location'     => $this->location,
            'owner'        => $this->login,
            'rights'       => $rights,
        );
        
        $status = $this->db->insertRow('files', $data);
        
        if ($status === false) {
            throw new Exception('failed to add file');
        }

        return $data;
    }

    /**
     * Создание нового каталога
     * 
     * @return void
     */
    public function newCatalog() 
    {
        $catalog = $this->_addCatalog();
        if ($catalog['rights'] === 'protected') {
            $accessList = empty($_POST['access_list']) ? [] : $_POST['access_list'];
            array_unshift($accessList, $catalog['virtual_path']);
            $this->csv->writeRow($accessList);       
        }                 
    }

    /**
     * Возвращает уникальное имя для добавляемых данных с названием $__name
     * 
     * @param string $__name Имя добавляемых данных
     * 
     * @return string 
     */
    private function _uniqName(string $__name)
    {
        // поиск файлов с таким же назаванием
        $catalog = $this->db->selectRow('cataloges', SearchBy::nameAndLocation($__name, $this->location));
        $file    = $this->db->selectRow('files', SearchBy::nameAndLocation($__name, $this->location));
        
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
    private function _addCatalog()
    {
        $name   = empty($_POST['catalog']) ? 'Новая папка' : $_POST['catalog'];
        $rights = empty($_POST['catalog_rights']) ? 'private' : $_POST['catalog_rights'];
        
        $_uniqName = $this->_uniqName($name);

        $cataloges = array(
            'name'         => $_uniqName,
            'location'     => $this->location,
            'virtual_path' => $this->location . '/' . $_uniqName, 
            'owner'        => $this->login,
            'rights'       => $rights,
        );

        $status = $this->db->insertRow('cataloges', $cataloges);

        if ($status === false) {
            throw new Exception('failed to add catalog');
        }
        
        return $cataloges;
    }
        
    /**
     * Удаление файла
     * 
     * @return void
     */
    public function deleteFile()
    {       
        $id = empty($_POST['delete_file']) ? '' : $_POST['delete_file'];

        $file = $this->db->selectRow('files', SearchBy::id($id));
        
        if (empty($file)) {
            throw new Exception('file do not exist');
        }
        
        if (unlink($file['path']) === false) {
            throw new Exception('failed to delete file on server');
        } 
        
        if ($this->db->deleteRow('files', SearchBy::id($id)) === false) {
            throw new Exception('failed to delete file in database');    
        }

        $this->csv->deleteRow($file['virtual_path']);
    }

    /**
     * Удаление каталога
     * 
     * @return void
     */
    public function deleteCatalog()
    {  
        $id = empty($_POST['delete_catalog']) ? '' : $_POST['delete_catalog']; 

        $catalog = $this->db->selectRow('cataloges', SearchBy::id($id), ['virtual_path']);
        
        if (empty($catalog)) {
            throw new Exception('catalog do not exist');
        }

        // проверка на непустой каталог
        $dataInThisCatalog = $this->db->selectRow('files', SearchBy::location($catalog['virtual_path']));
        
        if (!empty($dataInThisCatalog)) {
            throw new Exception('catalog is not empty');
        }

        if ($this->db->deleteRow('cataloges', SearchBy::id($id)) === false) {
            throw new Exception('failed to delete catalog');
        }
    }
    
    /**
     * Смена текущего расположения на один уровень вверх (подъем к корню)
     * 
     * @return void
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
     * 
     * @return void
     */
    public function downloadFile() 
    {
        $id   = empty($_POST['download']) ? '' : $_POST['download'];
        $file = $this->db->selectRow('files', SearchBy::id($id)); 
        if (empty($file)) {
            throw new Exception('file do not exist');
        }
        
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
            exit;
        }
    }

    /**
     * Генерация массива файлов текущего расположения
     * 
     * @return array Массив файлов текущего расположения.
     */
    private function _getLocalFiles()
    {
        $location = $this->location;
        
        $info = ['id', 'name', 'rights'];
        
        $files = $this->db->selectRows('files', SearchBy::location($location), $info);
     
        return $this->_formatingData($files);
    }

    /**
     * Генерация массива каталогов текущего расположения
     * 
     * @return array Массив каталогов текущего расположения.
     */
    private function _getLocalCataloges()
    {
        $location = $this->location;
        
        $info = ['id', 'name', 'rights'];

        $cataloges = $this->db->selectRows('cataloges', SearchBy::location($location), $info);

        return $this->_formatingData($cataloges);
    }

    /**
     * Генерация массива(цикла) пользователей для шаблонизатора
     * 
     * @return array Массив пользователей.
     */
    private function _getUsersList()
    {
        $users = $this->db->selectRows('users', SearchBy::all());
        
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
     * @param array $__data Массив данных.
     *  
     * @return array Отформатированный массив.
     */
    private function _formatingData(array $__data)
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