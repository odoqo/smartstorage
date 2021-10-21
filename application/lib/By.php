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

    public static function notLogin(string $__login)
    {
        return new static('notLogin', ['login' => $__login]);    
    } 

    public static function loginAndCookie(string $__login, string $__cookie)
    {
        $values = ['login' => $__login, 'cookie' => $__cookie];
        return new static('loginAndCookie', $values);
    }

    public static function loginAndPassword(string $__login, string $__password)
    {
        $values = ['login' => $__login, 'password' => $__password];
        return new static('loginAndPassword', $values);
    }

    public static function vPathAndOwner(string $__path, string $__owner)
    {
        $values = ['vPath' => $__path, 'owner' => $__owner];
        return new static('vPathAndOwner', $values); 
    }
    
}