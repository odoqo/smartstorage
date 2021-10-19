<?php

namespace application\models;

use application\lib\Db;
use application\core\Model;
use application\lib\By;

class Account extends Model
{   

    private $login;
    private $password;
    private $key;

    public function __construct()
    {
        parent::__construct();
        $this->login = $_POST['login'] ?? '';
        $this->password = $_POST['password'] ?? '';
        $this->key = $this->generateSalt(); 
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
            return 'error: insert fail';
        }

        $this->setCockie();

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

        $$this->setCockie();

        $_SESSION['auth'] = true;

        return 'success';
    }

    private function authentication()
    {
        $userRow = $this->db->selectFromTable('users', By::login($this->login));
        return $userRow['login'] === $this->login && $userRow['password'] === hash('sha256', $this->password);
    }

    private function setCockie() 
    {   
        unset($_COOKIE);   
             
        setcookie('login', $this->login, time() + 1000);
        setcookie('hash', $this->key, time() + 1000);
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
            'cookie'   => $this->key,
            'password' => $hash,
        );  

        return $this->db->insertIntoTable('users', $dataArr);
    }

    private function userExist()    
    {
       return $this->db->selectFromTable('users', By::login($this->login)) ?: false;
    }

    private function userNotExist()
    {
        return !$this->userExist();
    }

    private function checklogin()
    {
        // format
        $regexp = '/^[^@]+@[^@.]+\.[^@]+$/';
        return preg_match($regexp, $this->login);
    }

    private function checkPassword()
    {
        // format
        $regexp = '/^[A-Za-z0-9]{6,}$/';
        return preg_match($regexp, $this->password);
    }

}