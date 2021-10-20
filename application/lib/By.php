<?php 

namespace application\lib;

class By
{
    private string $mechanism;
    private array  $values;

    private function __construct($__mechanism, $__values)
    {
        $this->mechanism = $__mechanism;
        $this->values    = $__values;
    }

    public function getMechanism()
    {
        return $this->mechanism;
    }

    public function getValue()
    {
        return $this->values;
    }

    public static function id(string $__id)
    {
        return new static('id', ['id' => $__id]);
    }
    
    public static function login(string $__login)
    {
        return new static('login', ['login' => $__login]);
    }

    public static function loginAndCookie(string $__login, string $__cookie)
    {
        $values = ['login' => $__login, 'cookie' => $__cookie];
        return new static('loginAndCookie', $values);
    }

    public static function loginAndPassword(string $__login, string $__password)
    {
<<<<<<< HEAD
        $values = ['login' => $__login, 'cookie' => $__password];
        return new static('loginAndCookie', $values);
=======
        $values = ['login' => $__login, 'password' => $__password];
        return new static('loginAndPassword', $values);
>>>>>>> dev
    }

    
}