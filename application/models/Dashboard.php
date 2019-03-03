<?php

namespace application\models;

use application\controllers\AccountController;
use application\core\Model;

class Dashboard extends Model
{
    public function historyCount()
    {
        $params = [
            'uid' => $_SESSION['account']['id'],
        ];
        return $this->db->column('SELECT COUNT(id) FROM history WHERE uid = :uid', $params);
    }

    public function historyList($route)
    {
        phpversion();
        $max = 5;
        $params = [
            'max' => $max,
            'start' => ((($route['page'] ?? 1) - 1) * $max),
            'uid' => $_SESSION['account']['id'],
        ];
        return $this->db->row('SELECT * FROM history WHERE uid = :uid ORDER BY id DESC LIMIT :start, :max', $params);
    }

    public function referralsCount()
    {
        $params = [
            'uid' => $_SESSION['account']['id'],
        ];
        return $this->db->column('SELECT COUNT(id) FROM accounts WHERE ref = :uid', $params);
    }

    public function referralsList($route)
    {
        $max = 10;
        $params = [
            'max' => $max,
            'start' => ((($route['page'] ?? 1) - 1) * $max),
            'uid' => $_SESSION['account']['id'],
        ];
        return $this->db->row('SELECT login, email FROM accounts WHERE ref = :uid ORDER BY id DESC LIMIT :start, :max', $params);
    }
    public function tariffsCount()
    {
        $params = [
            'uid' => $_SESSION['account']['id'],
        ];
        return $this->db->column('SELECT COUNT(id) FROM tariffs WHERE uid = :uid', $params);
    }

    public function tariffsList($route)
    {
        $max = 10;
        $params = [
            'max' => $max,
            'start' => ((($route['page'] ?? 1) - 1) * $max),
            'uid' => $_SESSION['account']['id'],
        ];
        return $this->db->row('SELECT * FROM tariffs WHERE uid = :uid ORDER BY id DESC LIMIT :start, :max', $params);
    }

    public function creatRefWithdraw()
    {
        // Записываем данные о реф. балансе в перемённую для дальнейшего использования
        $amount = $_SESSION['account']['refBalance'];
        // Ощичаем сессию c реф. балансом
        $_SESSION['account']['refBalance'] = 0;

        // Очищаем реферальный баланс в БД
        $params = [
            'id' => $_SESSION['account']['id'],
        ];
        $this->db->query('UPDATE accounts SET refBalance = 0 WHERE id = :id', $params);

        // Создаём заявку на вывод средств с рефералов
        $params = [
            'id' => '',
            'uid' => $_SESSION['account']['id'],
            'unixTime' => time(),
            'amount' => $amount,
        ];
        $this->db->query('INSERT INTO ref_withdraw VALUES (:id, :uid, :unixTime, :amount)', $params);

        // Добавляем запись  о выводе реферальных средств в историю в аккаунте
        $params = [
            'id' => '',
            'uid' => $_SESSION['account']['id'],
            'unixTime' => time(),
            'description' => 'Вывод реферального вознаграждения, сумма ' . $amount . ' ₽',
        ];
        $this->db->query('INSERT INTO history VALUES (:id, :uid, :unixTime, :description)', $params);
    }
}