<?php

namespace application\controllers;

use application\core\Controller;
use application\lib\Pagination;

class DashboardController extends Controller
{
    // Покупка инвестиции
    public function investAction()
    {
        $vars = [
            'tariff' => $this->tariffs[$this->route['id']],
        ];
        $this->view->render("Инвестиция",$vars);
    }


    // Список инвестиций пользователя в личном кабинете
    public function tariffsAction()
    {
        $pagination = new Pagination($this->route, $this->model->tariffsCount());
        $vars = [
            'pagination' => $pagination->get(),
            'list' => $this->model->tariffsList($this->route),
        ];
        $this->view->render('Тарифы', $vars);
    }

    // История
    public function historyAction()
    {
        $pagination = new Pagination($this->route, $this->model->historyCount(), 5);
        $vars = [
            'pagination' => $pagination->get(),
            'list' => $this->model->historyList($this->route),
        ];
        $this->view->render("История",$vars);
    }

    // Список рефералов и запрос на вывод средст в с них
    public function referralsAction()
    {
        if (!empty($_POST)) {
            if ($_SESSION['account']['refBalance'] <= 0) {
                $this->view->message('Ошибка', 'Реферальный баланс пуст');
            }
            $this->model->creatRefWithdraw();
            $this->view->message('Успех', 'Заявка на вывод создана');
        }
        $pagination = new Pagination($this->route, $this->model->referralsCount());
        $vars = [
            'pagination' => $pagination->get(),
            'list' => $this->model->referralsList($this->route),
        ];
        $this->view->render('Рефералы', $vars);
    }


}