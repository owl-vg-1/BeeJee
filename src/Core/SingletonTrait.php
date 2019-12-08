<?php

namespace App\Core;


trait SingletonTrait
{
    protected static $instance;
    final public static function getInstance()
    {
        return static::$instance ?? static::$instance = new static;
    }
    final private function __construct()
    {
    }
}