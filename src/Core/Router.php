<?php

namespace App\Core;

use App\Core\Config;
use App\Controller\AuthController;
use App\Controller\ErrorController;
use App\Core\URL;

class Router
{
    function __construct()
    {
        $uriData = URL::getInstance()->decodeUri($_SERVER['REQUEST_URI']);
        

        if (!empty($uriData)) {
            $_GET = array_merge($_GET, $uriData['vars']);
            $urlArray = explode('/', $uriData['handler']);
            $_GET['t'] = $urlArray[0];
            $_GET['a'] = $urlArray[1];
        } 

        $this->controllerName = ($_GET["t"] ?? Config::DEFAULT_CONTROLLER);
        $this->actionName = 'action' . ($_GET["a"] ?? Config::DEFAULT_ACTION);
        
       

    }

    public function run()
    {

        if (AuthController::CheckRights($this->controllerName)) {

            $className = "App\\Controller\\{$this->controllerName}Controller";

            if (class_exists($className)) {
                $MVC = new $className();

                if (method_exists($MVC, $this->actionName)) {
                    $MVC->{$this->actionName}();
                } else {
                    (new ErrorController)->notFound("нет такого метода: $this->actionName");
                }
            } else {
                (new ErrorController)->notFound("нет такого класса: $this->controllerName");

            }
        }else{
            (new ErrorController)->forbidden("У Вас недостаточно прав");

        }
    }
}
