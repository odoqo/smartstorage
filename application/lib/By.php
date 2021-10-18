<?php 

namespace application\lib;

class By
{
    private string $mechanism;
    private string $value;

    private function __construct($__mechanism, $__value)
    {
        $this->mechanism = $__mechanism;
        $this->value     = $__value;
    }

    /**
     * @return string
     */
    public function getMechanism()
    {
        return $this->mechanism;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    public static function Id(string $__id)
    {
        return new static('id', $__id);
    }
    
    public static function login(string $__login)
    {
        return new static('login', $__login);
    }

    
}