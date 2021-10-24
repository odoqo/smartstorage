<?php

namespace application\models;

use application\lib\Db;
use application\core\Model;
use application\lib\By;
use Exception;

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

    public function logout()
    {
        setcookie('login', '', time() - 60*5, '/');
        setcookie('key', '', time() - 60*5, '/');
        unset($_SESSION);
        session_destroy();
    }

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

    private function authentication()
    {
        $hash = hash('sha256', $this->password);
        return $this->db->selectRow('users', By::loginAndPassword($this->login,  $hash));
    }

    private function setCockie() 
    {   
        $key = hash('sha256', $this->generateSalt()); 
             
        setcookie('login', $this->login, time() + 60*60, '/');
        setcookie('key', $key, time() + 60*60, '/');

        $setsFields = ['cookie' => $key];
        $this->db->updateFields('users', By::login($this->login), $setsFields);
    }

    private function generateSalt()
	{
		$salt = '';
		$saltLength = 8;
		for($i = 0; $i < $saltLength; ++$i) {
			$salt .= chr(mt_rand(33, 126));
		}

		return $salt;
	}

    private function addUser() 
    {
        $hash = hash('sha256', $this->password);

        $dataArr = array(
            'login'    => $this->login,
            'password' => $hash,
        );  

        return $this->db->insertRow('users', $dataArr);
    }

    private function userExist()    
    {
       return $this->db->selectRow('users', By::login($this->login)) ?: false;
    }

    private function userNotExist()
    {
        return !$this->userExist();
    }

    private function checklogin()
    {
        // format
        $regexp = '/^[^@]+@[^@.]{0,10}\.[^@]{1,4}$/';
        return preg_match($regexp, $this->login);
    }

    private function checkPassword()
    {
        // format
        $regexp = '/^[A-Za-z0-9]{6,}$/';
        return preg_match($regexp, $this->password);
    }

}