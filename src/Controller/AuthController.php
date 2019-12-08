<?php

namespace App\Controller;

session_start();

use App\Core\Config;


class AuthController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->view->setViewPath(__DIR__ . '/../../templates/Auth/');
        $this->view->setLayoutsPath(__DIR__.'/../../templates/_layouts/mainLayout.php');
    }

    public function actionLoginForm()
    {
        $this->render("Form", [
            'formPath' => '?t=' . $this->classNameNP() . '&a=CheckLogin'
        ]);
    }

    public function actionCheckLogin()
    {
        $users_array = json_decode(file_get_contents(Config::DATA_USERS), true);
        if (empty($_POST['login']) || empty($_POST['password'])) {
            $_SESSION['errorsUser'][] = 'Пустой логин или(и) пароль!';
            $this->redirect('?t=Error&a=ShowErrorsUser');
            exit();
        }

        if (isset($_POST['login']) && $_POST['password'] == $users_array[$_POST['login']]) {
            $_SESSION['autorized_user'] = $_POST['login'];
            $this->redirect('?t=Task&a=Show&');
        } else {
            $_SESSION['errorsUser'][] = "Неверный логин или пароль!";
            $this->redirect('?t=Error&a=ShowErrorsUser');
        }
    }

    public function actionLogout()
    {
        unset($_SESSION['autorized_user']);
        $this->redirect('?t=Task&a=Show');
    }

    public static function CheckRights(string $controllerName)
    {
        $rights_array = json_decode(file_get_contents(Config::USERS_RIGHTS), true);

        if (isset($_SESSION['autorized_user'])) {
            return in_array(strtolower($controllerName), array_map('strtolower', $rights_array["admin"]));
        } else {
            return in_array(strtolower($controllerName), array_map('strtolower', $rights_array["default"]));
        }
    }
}
