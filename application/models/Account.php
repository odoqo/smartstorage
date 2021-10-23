<?php
/*
 * функции для действий на странице входа/регистрации
 */
namespace application\models;

use application\lib\Db;
use application\core\Model;
use application\lib\By;

class Account extends Model
{   

    private $login;
    private $password;

    public function __construct()
    {
        parent::__construct();
        $this->login    = $_POST['login'] ?? '';
        $this->password = $_POST['password'] ?? '';
    }

    //регистрации
    public function signUp() 
    {
        if (!$this->checklogin() || !$this->checkPassword()) {
            return 'error: unvalid input data';
        }

        if ($this->userExist()) {
            return 'error: user exists'; 
        }

        if (!$this->addUser()) {
            return 'error: insert fail';
        }

        $this->setCockie();

        $_SESSION['auth'] = true;
        $_SESSION['location'] = $this->login;

        return 'success'; 
    }

    //вход
    public function signIn()
    {
        if (!$this->checklogin() || !$this->checkPassword()) {
            return 'error: unvalid input data';
        }

        if ($this->userNotExist()) {
            return 'error: user do not exists'; 
        }   

        if(!$this->authentication()) {
            return 'error: invalid password';
        }

        $this->setCockie();

        $_SESSION['auth'] = true;
        $_SESSION['location'] = $this->login;

        return 'success';
    }

    //ds[jl
    public function logout()
    {        
        setcookie('login', '', time() - 60*5, '/');
        setcookie('key', '', time() - 60*5, '/');
        unset($_SESSION);
        session_destroy();
    }

    //проверк установленных куки(пользователь уже вошел или нет)
    public function userLogged() 
    {
        if (isset($_SESSION['auth']) && $_SESSION['auth'] === true) {
            return true;
        } 

        if (isset($_COOKIE['login']) && isset($_COOKIE['key'])) {
            $login = $_COOKIE['login'];
            $key   = $_COOKIE['key'];
            return $this->db->selectRow('users', By::loginAndCookie($login, $key)) ;
        }
    }

    //аунтификация
    private function authentication()
    {
        $hash = hash('sha256', $this->password);
        return $this->db->selectRow('users', By::loginAndPassword($this->login,  $hash));
    }

    //установка куки
    private function setCockie() 
    {   
        $key = hash('sha256', $this->generateSalt()); 
             
        setcookie('login', $this->login, time() + 60*60*8, '/');
        setcookie('key', $key, time() + 60*60*8, '/');

        $setsFields = ['cookie' => $key];
        $this->db->updateFields('users', By::login($this->login), $setsFields);
    }

    //для безопасности - генерируем случайную строку
    private function generateSalt()
    {
            $salt = '';
            $saltLength = 8;
            for($i = 0; $i < $saltLength; ++$i) {
            $salt .= chr(mt_rand(33, 126));
            }

            return $salt;
    }

    //добавление пользователя в базу    
    private function addUser() 
    {
        $hash = hash('sha256', $this->password);

        $dataArr = array(
            'login'    => $this->login,
            'password' => $hash,
        );  

        return $this->db->insertRow('users', $dataArr);
    }
    
    //проверка есть ли пользователь с таким логином
    private function userExist()    
    {
       return $this->db->selectRow('users', By::login($this->login)) ?: false;
    }

    //проверка есть ли пользователь с таким логином
    private function userNotExist()
    {
        return !$this->userExist();
    }

    //проверк логина
    private function checklogin()
    {
        // format
        $regexp = '/^[^@]+@[^@.]+\.[^@]+$/';
        return preg_match($regexp, $this->login);
    }

    //проверк пароля
    private function checkPassword()
    {
        // format
        $regexp = '/^[A-Za-z0-9]{6,}$/';
        return preg_match($regexp, $this->password);
    }

}