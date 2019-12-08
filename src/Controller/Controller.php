<?php
namespace App\Controller;

use App\View\View;
use ReflectionClass;

abstract class Controller {
    
    public $view;

    function __construct() {
        $this->view = new View();
    }

    function render($viewName, $viewData = []) {
        $this->view->render($viewName, $viewData);
    }

    function redirect($location) {
        header ("Location: ".$location);
    }

    function classNameNP() {
        return strtolower(preg_replace('/Controller$/', '', (new ReflectionClass($this))->getShortName()));
    }

    function currentActionNameNP() {
        return strtolower(preg_replace('/^action/', '', debug_backtrace()[1]['function']));
    }

}

?>