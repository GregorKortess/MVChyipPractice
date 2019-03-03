<?php

namespace application\controllers;

use application\core\Controller;

class AccountController extends Controller
{

    // Основная суть большинства методов в этом классе заключается в том что ,
    // в начале  валидируется и проверяются входящие данные , a потом
    // используется основная функция из модели для действий с БД.

    // Register
    public function registerAction()
    {
        if (!empty($_POST)) {
            // Проверка данных
            if (!$this->model->validate(['email', 'login', 'wallet', 'password','ref'], $_POST)) {
                $this->view->message('Error', $this->model->error);
            } elseif ($this->model->checkEmailExists($_POST['email'])) {
                $this->view->message('Error', 'Этот Email уже используется');
            } elseif (!$this->model->checkLoginExists($_POST['login'])) {
                $this->view->message('Error', $this->model->error);
            }
            $this->model->register($_POST );
            $this->view->message('Регистрация завершена', "Потвердите свой EMAIL адресс");
        }
        $this->view->render('Регистрация');
    }

    // Потверждение регистрации
    public function confirmAction()
    {
        if (!$this->model->checkTokenExists($this->route['token'])) {
            $this->view->redirect('account/login');
        }
        $this->model->activate($this->route['token']);
        $this->view->render("Регистрация завершена");
    }

    // Вход
    public function loginAction()
    {
        if (!empty($_POST)) {
            if (!$this->model->validate(['login', 'password'], $_POST)) {
                $this->view->message('error', $this->model->error);
            }
            elseif (!$this->model->checkData($_POST['login'], $_POST['password'])) {
                $this->view->message('error', 'Логин или пароль указан неверно');
            }
            elseif (!$this->model->checkStatus('login', $_POST['login'])) {
                $this->view->message('error', $this->model->error);
            }
            $this->model->login($_POST['login']);
            $this->view->location("account/profile");
        }
        $this->view->render('Вход');
    }


    // Выход из аккаунта
    public function logoutAction()
    {
        unset($_SESSION['account']);
        $this->view->redirect('account/login');
    }

    // Востановление пароля
    public function recoveryAction()
    {
        if (!empty($_POST)) {
            // Проверка данных
            if (!$this->model->validate(['email'], $_POST)) {
                $this->view->message('Error', $this->model->error);
            } elseif ($this->model->checkEmailExists($_POST['email'])) {
                $this->view->message('Error', 'Пользователь не найден');
            }
            elseif (!$this->model->checkStatus('email', $_POST['email'])) {
                $this->view->message('error', $this->model->error);
            }
            $this->model->recovery($_POST );
            $this->view->message('Успех', "Запрос на востановление пароля отправлен на Email");
        }
        $this->view->render('Востановление пароля');
    }

    // Сброс пароля
    public function resetAction()
    {
        if (!$this->model->checkTokenExists($this->route['token'])) {
            $this->view->redirect('account/login');
        }
        $password = $this->model->reset($this->route['token']);
        $vars = [
          'password' => $password,
        ];
        $this->view->render("Пароль сброшен",$vars);
    }

    // Профиль
    public function profileAction()
    {
        if (!empty($_POST)) {
            if (!$this->model->validate(['email', 'wallet'], $_POST)) {
                $this->view->message('error', $this->model->error);
            }
            $id = $this->model->checkEmailExists($_POST['email']);
            if ($id and $id != $_SESSION['account']['id']) {
                $this->view->message('error', 'Этот E-mail уже используется');
            }
            if (!empty($_POST['password']) and !$this->model->validate(['password'], $_POST)) {
                $this->view->message('error', $this->model->error);
            }
            $this->model->save($_POST);
            $this->view->message('Успех', 'Сохранено');
        }
        $this->view->render('Профиль');
    }

}

