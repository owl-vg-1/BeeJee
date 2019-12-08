<?php
session_start();
include "../src/autoload.php";
use App\Core\Router;
$obj = new Router();
$obj->run();
?>