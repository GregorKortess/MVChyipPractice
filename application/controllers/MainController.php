<?php

namespace application\controllers;
use application\core\Controller;


class MainController extends Controller
{
    // Список всех возможных тарифоф(инвестиций) на главной странице
    public function indexAction()
    {
        $vars = [
            'tariffs' => $this->tariffs,
            ];

        $this->view->render("Главная страница",$vars);
    }

}