<?php

namespace application\models;

use application\core\Model;

class Merchant extends Model
{
    public function validatePerfectMoney($post, $tariff)
    {
        // Надо настроить под себя для корректной работы оплаты на сайте
        $params =
            $post['PAYMENT_ID'] . ':' .
            $post['PAYEE_ACCOUNT'] . ':' .
            $post['PAYMENT_AMOUNT'] . ':' .
            $post['PAYMENT_UNITS'] . ':' .
            $post['PAYMENT_BATCH_NUM'] . ':' .
            $post['PAYER_ACCOUNT'] . ':' .
            strtoupper(md5('secret')) . ':' .
            $post['TIMESTAMPGMT'];

        list($tid, $uid) = explode(',', $post['PAYMENT_ID']);
        $tid += 0;
        $uid += 0;
        $amount = $post['PAYMENT_AMOUNT'] + 0;
        /* if условие снизу надо расскоментировать если хотите сделать настоящую оплату */
        //if (strtoupper(md5($params)) != $post['V2_HASH']) {
            //return false;
        //}
        if ($post['PAYMENT_UNITS'] != 'RUB') {
            return false;
        } elseif (!isset($tariff[$tid])) {
            return false;
        } elseif ($amount > $tariff[$tid]['max'] or $amount < $tariff[$tid]['min']) {
            return false;
        }
        return [
            'tid' => $tid,
            'uid' => $uid,
            'amount' => $amount,
        ];
    }

    // Создание тарифа при покупки пользователем
    public function createTariff($data, $tariff)
    {
        // Проверка наличия реферала
        $dataRef = $this->db->column('SELECT ref FROM accounts WHERE id =:id', ['id' => $data['uid']]);
        if ($dataRef === false) {
            return false;
        }
        if ($dataRef != 0) {
            $refSum =  round((($data['amount'] * 5) / 100), 2);
            $params = [
                'sum' => $refSum,
                'id' => $dataRef,
            ];
            $this->db->query('UPDATE accounts SET refBalance = refBalance + :sum WHERE id = :id', $params);
            $params = [
                'id' => '',
                'uid' => $dataRef,
                'unixTime' => time(),
                'description' => "Сумма реферального вознаграждения ".$refSum.'₽',

            ];
            $this->db->query('INSERT INTO history VALUES (:id, :uid, :unixTime, :description)', $params);
        }

        // Запись тарифа после покупки в БД
        $params = [
            'id' => '',
            'uid' => $data['uid'],
            'sumIn' => round($data['amount'], 2),
            'sumOut' => round($data['amount'] + (($data['amount'] * $tariff['percent']) / 100), 2),
            'percent' => $tariff['percent'],
            'unixTimeStart' => time(),
            'unixTimeFinish' => strtotime('+' . $tariff['hour'] . ' hours'),

        ];
        $this->db->query('INSERT INTO tariffs VALUES (:id, :uid, :sumIn, :sumOut, :percent , :unixTimeStart , :unixTimeFinish)', $params);

        // Запись в историю
        $params = [
            'id' => '',
            'uid' => $data['uid'],
            'unixTime' => time(),
            'description' => "Инвестиция под номером ".$this->db->lastInsertId(),

        ];
        $this->db->query('INSERT INTO history VALUES (:id, :uid, :unixTime, :description)', $params);

        exit('ok');
    }

}