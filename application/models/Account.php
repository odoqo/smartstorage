<?php

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
        $this->login = $_POST['login'] ?? '';
        $this->password = $_POST['password'] ?? ''; 
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

        $this->setCockie();

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
             
        $hash = hash('sha256', $this->password);
        setcookie('login', $this->login, time() + 1000);
        setcookie('hash', $hash, time() + 1000);
    }

    private function addUser() 
    {
        $hash = hash('sha256', $this->password);

        $dataArr = array(
            'login'    => $this->login,
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