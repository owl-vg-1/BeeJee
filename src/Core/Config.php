<?php
//+++

namespace App\Core;

class Config {
    public const MYSQL = [
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname' => 'beejee'
    ];
    public const FILE_NAME = __DIR__.'/../../storage/data.mysql';
    public const DATA_USERS = __DIR__.'/../../src/core/users.json';
    public const USERS_RIGHTS = __DIR__.'/../../src/core/rights.json';
    public const DEFAULT_CONTROLLER = 'Task';
    public const DEFAULT_ACTION = 'Show';

}


?>