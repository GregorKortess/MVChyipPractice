<?php

namespace application\controllers;

use application\core\Controller;
use application\lib\Pagination;

class AdminController extends Controller
{

    // Подключаем основной стиль для админ панели
    public function __construct($route)
    {
        parent::__construct($route);
        $this->view->layout = 'admin';
    }

    // Вход в админ панель, проверяем входные данные с данными из admin.php в папке config
    public function loginAction()
    {
        if (isset($_SESSION['admin'])) {
            $this->view->redirect('admin/withdraw');
        }
        if (!empty($_POST)) {
            if (!$this->model->loginValidate($_POST)) {
                $this->view->message('error', $this->model->error);
            }
            $_SESSION['admin'] = true;
            $this->view->location('admin/withdraw');
        }
        $this->view->render('Вход');
    }

    // Потверждения выплаты денег
    public function withdrawAction()
    {
        // Потверждения выплаты реф. денег
        if (!empty($_POST)) {
            if ($_POST['type'] == 'ref') {
                $result = $this->model->withdrawRefComplete($_POST['id']);
                if ($result) {
                    $this->view->location('admin/withdraw');
                } else {
                    $this->view->message('error', 'Ошибка обработки запроса');
                }
                // потверждение выплаты денег с тарифоф
            } elseif ($_POST['type'] == 'tariff') {
                $result = $this->model->withdrawTariffsComplete($_POST['id']);
                if ($result) {
                    $this->view->location('admin/withdraw');
                } else {
                    $this->view->message('error', 'Ошибка обработки запроса');
                }
            }
        }
        // Данные для отоброжения запросов на выплаты
        $vars = [
            'listRef' => $this->model->withdrawRefList(),
            'listTariffs' => $this->model->withdrawTariffsList(),
        ];
        $this->view->render('Заказы на вывод средств', $vars);
    }

    // История в админ панеле
    public function historyAction()
    {
        $pagination = new Pagination($this->route, $this->model->historyCount());
        $vars = [
            'pagination' => $pagination->get(),
            'list' => $this->model->historyList($this->route),
        ];
        $this->view->render('История', $vars);
    }

    // Список всех инвестиций в админке
    public function tariffsAction()
    {
        $pagination = new Pagination($this->route, $this->model->tariffsCount());
        $vars = [
            'pagination' => $pagination->get(),
            'list' => $this->model->tariffsList($this->route),
        ];
        $this->view->render('Список инвестиций', $vars);
    }

    // Выход из админ панели
    public function logoutAction()
    {
        unset($_SESSION['admin']);
        $this->view->redirect('admin/login');
    }

}