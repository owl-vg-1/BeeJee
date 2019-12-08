<?php

namespace App\Controller;

use App\Model\DB_entity;
use App\Core\Config;
use mysqli;

class TaskController extends Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->view->setViewPath(__DIR__.'/../../templates/Task/');
        $this->view->setLayoutsPath(__DIR__.'/../../templates/_layouts/mainLayout.php');
        $this->tableTask = new DB_entity(new mysqli(Config::MYSQL['host'], Config::MYSQL['username'], Config::MYSQL['password'], Config::MYSQL['dbname']), 'task');
    }


    public function actionShow () {
        // Установка значений по умолчанию
        if (isset($_GET['field'])) {
            $defaultField = $_GET['field'];
        } else {
            $defaultField = 'id';
        }

        if (isset($_GET['sort'])) {
            $defaultSort = $_GET['sort'];
        } else {
            $defaultSort = 'ASK';
        }

        if (!isset($_GET['currentPage'])) {
            $_GET['currentPage'] = 1;
        }

        $this->tableTask->set_page_size(3)->set_page($_GET['currentPage']-1);


        // Сортировка с пагинацией
        if ($_GET['pagination']) {
            if ($_GET['field'] == $_GET['lastSortField'] && $_GET['lastSort'] == 'ASK' ) {
                $this->tableTask->add_order_by_asc($_GET['field']);
                $defaultSort = "ASK";
            } elseif ($_GET['field'] == $_GET['lastSortField'] && $_GET['lastSort'] == 'DESK') {
                $this->tableTask->add_order_by_desc($_GET['field']);
                $defaultSort = "DESK";
            } elseif ($_GET['field'] != $_GET['lastSortField'] && $_GET['lastSort'] == 'DESK') {
                $this->tableTask->add_order_by_desc($_GET['field']);
                $defaultSort = "DESK";
            } else {
                $this->tableTask->add_order_by_desc($_GET['field']);
                $defaultSort = "DESK";
            }
        } else {
            if ($_GET['field'] == $_GET['lastSortField'] && $_GET['lastSort'] == 'ASK' ) {
                $this->tableTask->add_order_by_desc($_GET['field']);
                $defaultSort = "DESK";
            } elseif ($_GET['field'] == $_GET['lastSortField'] && $_GET['lastSort'] == 'DESK') {
                $this->tableTask->add_order_by_asc($_GET['field']);
                $defaultSort = "ASK";
            } elseif ($_GET['field'] != $_GET['lastSortField'] && $_GET['lastSort'] == 'DESK') {
                $this->tableTask->add_order_by_asc($_GET['field']);
                $defaultSort = "ASK";
            } else {
                $this->tableTask->add_order_by_asc($_GET['field']);
                $defaultSort = "ASK";
            }
        }
        

        $this->render("task", [
            'tableTask' => $this->tableTask->query(),
            'tableTaskHeader' =>  $this->tableTask->get_field_comments(),
            'lastSortField' => $defaultField,
            'lastSort' => $defaultSort,
            'sortPath' => '?t='.$this->classNameNP()."&a=Show&",
            'paginathionPath' => '?t='.$this->classNameNP()."&a=Show&field=$_GET[field]&lastSortField=$_GET[field]&lastSort=$defaultSort&pagination=1",
            'formPath' => '?t='.$this->classNameNP()."&a=Add&",
            'editTaskPath' => '?t='.$this->classNameNP()."&a=EditTask",
            'countPage' => ceil($this->tableTask->row_count() / 3),
            'access' => ($_SESSION['autorized_user'] == 'admin') ? true : false,
        ]);
    }

    public function actionAdd() 
    {
        // проверка на пустые поля
        if ($_POST['nameUser'] == '') {
            $_SESSION['errorsUser'][] = "Не введено имя!";
        }
        if ($_POST['email'] == '') {
            $_SESSION['errorsUser'][] = "Не введен email!";
        }
        if ($_POST['task'] == '') {
            $_SESSION['errorsUser'][] = "Не введена задача!";
        }
        // проверка валидности email
        if (!preg_match('/[\.a-z0-9_\-]+[@][a-z0-9_\-]+([.][a-z0-9_\-]+)+[a-z]{1,4}/i', $_POST['email']))
        {
            $_SESSION['errorsUser'][] = "Введенный email не корректен!";
        }

        //Вывод ошибок пользователю
        if (isset($_SESSION['errorsUser'][0])) {
            $this->redirect('?t=Error&a=ShowErrorsUser');
            exit();
        }

        // Защита по полю task
        $search = array("<", ">");
        $replace = array("&#60", "&#62");
        $_POST['task']= str_replace($search, $replace, $_POST['task']);
        
        // Добавление данных в БД
        $this->tableTask->add($_POST);
        $_SESSION['addTask'] = "Задача добавлена!";
        $this->redirect('?t='.$this->classNameNP().'&a=Show');
    }

    //получение данных для редактирования
    public function actionEditTask() {
        $editTask = $this->tableTask->get_row_by_id($_GET['id']);
        $_SESSION['editData'] = $editTask;
        $_SESSION['editData']['id'] .= $_GET['id'];
        $this->render("editForm", [
            'editTask' => $editTask,
            'editPath' => '?t='.$this->classNameNP()."&a=Edit",
        ]);

    }

    public function actionEdit() {
        if ($_SESSION['autorized_user'] == 'admin') {
            // Для регистрации выполнения задачи
            $data=$this->tableTask->get_row_by_id($_GET['id']);
            if($_POST['done']==1) {
                $editId = $_GET['id'];
            }
            if ($data['done'] == '00') {
                $_POST['done'] = '10';
            } elseif ($data['done'] == '01') {
                $_POST['done'] = '11';
            }
            
            // Для редактирования задания
            if ($_SESSION['editData']['done'] == '00') {
                $_POST['done'] = '01';
            } elseif ($_SESSION['editData']['done'] == '10') {
                $_POST['done'] = '11';
            }

            if(isset($_SESSION['editData']['id'])){
                $editId = $_SESSION['editData']['id'];
                unset($_SESSION['editData']);
            }

            $this->tableTask->edit($editId, $_POST);
            $this->redirect('?t='.$this->classNameNP().'&a=Show');
        } else {
            $this->redirect('?t=Auth&a=LoginForm');;
        }
        
    }

}