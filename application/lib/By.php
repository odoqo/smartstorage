<?php 

namespace application\lib;

/**
 * Вспомогательный класс для Db. Объект хранит способ поиска элемента в базе.
 * 
 * @author odoqo 
 */
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

    public static function all()
    {
        return new static('all', []);
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

    public static function locationAndOwner(string $__location, string $__owner)
    {
        $values = ['location' => $__location, 'owner' => $__owner];
        return new static('locationAndOwner', $values); 
    }

    public static function nameAndLocation(string $__name, string $__location)
    {
        $values = ['location' => $__location, 'name' => $__name];
        return new static('nameAndLocation', $values); 
    }
    
    public static function location(string $__location)
    {
        $values = ['location' => $__location];
        return new static('location', $values); 
    }

    public static function virtualPath(string $__virtualPath)
    {
        $values = ['virtualPath' => $__virtualPath];
        return new static('virtualPath', $values); 
    }

    public static function idAndOwner(string $__id, string $__owner)
    {
        return new static('idAndOwner', ['id' => $__id, 'owner' => $__owner]);
    }
}