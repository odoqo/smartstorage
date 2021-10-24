<?php

namespace application\models;

use application\core\Model;
use application\lib\By;
use Exception;

/**
 * Модель содержит основную логику действий с учетными записями пользователей 
 */
class Account extends Model
{   
    private $login;
    private $password;

    public function __construct()
    {
        parent::__construct();
        $this->login    = empty($_POST['login']) ? '' : $_POST['login'];
        $this->password = empty($_POST['password']) ? '' : $_POST['password'];
    }

    /**
     * Регистрация аккаунта
     */
	public function signUp() 
    {
        if (!$this->checklogin() || !$this->checkPassword()) {
            return 'error: unvalid input data';
        }   

        if ($this->userExist()) {
            return 'error: user exists'; 
        }

        if (!$this->addUser()) {
            throw new Exception('error: insert fail');
        }

        $this->setCockie();

        $_SESSION['login']    = $this->login;
        $_SESSION['location'] = $this->login;
        $_SESSION['auth']     = true;
        
        return 'success'; 
    }

    /**
     * Вход в аккаунт
     */
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

        $_SESSION['login']    = $this->login;
        $_SESSION['location'] = $this->login;
        $_SESSION['auth']     = true;

        return 'success';
    }

    /**
     * Выход из аккаунта
     */
    public function logout()
    {
        setcookie('login', '', time() - 60*5, '/');
        setcookie('key', '', time() - 60*5, '/');
        unset($_SESSION);
        session_destroy();
    }

    /**
     * Авторизация 
     * 
     * @return bool
     */
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

    /**
     * Аутентификация
     * 
     * @return bool
     */
    private function authentication()
    {
        $hash = hash('sha256', $this->password);
        return $this->db->selectRow('users', By::loginAndPassword($this->login,  $hash));
    }

    /**
     * Установка кук
     */
    private function setCockie() 
    {   
        $key = hash('sha256', $this->generateSalt()); 
             
        setcookie('login', $this->login, time() + 60*60, '/');
        setcookie('key', $key, time() + 60*60, '/');

        $setsFields = ['cookie' => $key];
        $this->db->updateFields('users', By::login($this->login), $setsFields);
    }

    /**
     * Генерация случайной строки
     * 
     * @return string
     */
    private function generateSalt()
	{
		$salt = '';
		$saltLength = 8;
		for($i = 0; $i < $saltLength; ++$i) {
			$salt .= chr(mt_rand(33, 126));
		}

		return $salt;
	}

    /**
     * Добавление учетной записи
     * 
     * @return bool
     */
    private function addUser() 
    {
        $hash = hash('sha256', $this->password);

        $dataArr = array(
            'login'    => $this->login,
            'password' => $hash,
        );  

        return $this->db->insertRow('users', $dataArr);
    }

    /**
     * Проверка на уже существующий аккаунт

     */
    private function userExist()    
    {
       return $this->db->selectRow('users', By::login($this->login));
    }

    /**
     * Проверка на несуществующий аккаунт
     */
    private function userNotExist()
    {
        return !$this->userExist();
    }

    /**
     * Проверка логина
     */
    private function checklogin()
    {
        $regexp = '/^[^@]+@[^@.]{0,10}\.[^@]{1,4}$/';
        return preg_match($regexp, $this->login);
    }

    /**
     * Проверка пароля
     */
    private function checkPassword()
    {
        $regexp = '/^[A-Za-z0-9]{6,}$/';
        return preg_match($regexp, $this->password);
    }

}