<?php

namespace application\controllers;
use application\core\Controller;


class MerchantController extends Controller
{
    public function perfectMoneyAction()
    {

        // Псевдо платёж для тестирования
        /* $_POST['PAYMENT_AMOUNT'] = 10000;
         $_POST['PAYEE_ACCOUNT'] = '';
         $_POST['PAYMENT_BATCH_NUM'] = '';
         $_POST['PAYER_ACCOUNT'] = '';
         $_POST['TIMESTAMPGMT'] = '';
         $_POST['PAYMENT_UNITS'] = 'RUB';
         $_POST['PAYMENT_ID'] = '3,1'; */

        // Валидация
        $data = $this->model->validatePerfectMoney($_POST,$this->tariffs);
        if(!$data)
        {
            $this->view->errorCode(403);
        }
        // В случае удачной покупки добавляем тариф в бд
        $this->model->createTariff($data,$this->tariffs[$data['tid']]);
    }
}