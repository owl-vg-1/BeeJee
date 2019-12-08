<?php

namespace App\Core;


trait DispatcherTrait
{
    public $dispatcher = [
        'login' => 'Auth/LoginForm',
        'logout' => 'Auth/Logout',
    ];
}