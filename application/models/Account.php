<?php

namespace application\models;

use application\core\Model;

class Account extends Model
{
    public function validate($input, $post)
    {
        // Правила для валидации
        $rules = [
            'email' => [
                'pattern' => '#^([A-Za-z0-9_.-]{1,20}+)@([a-z0-9_.-]+)\.([a-z\.]{2,10})$#',
                'message' => 'E-mail адрес указан неверно',
            ],
            'login' => [
                'pattern' => '#^[a-zA-z0-9]{3,15}$#',
                'message' => 'Логин указан неверно (разрешены только латинские буквы и цифры от 3 до 15 символов',
            ],
            'ref' => [
                'pattern' => '#^[a-zA-Z0-9]{3,15}$#',
                'message' => 'Логин пригласившего указан неверно',
            ],
            'wallet' => [
                'pattern' => '#^[A-zA-Z0-9]{3,15}$#',
                'message' => 'Кошелек Perfect Money указан неверно',
            ],
            'password' => [
                'pattern' => '#^[a-zA-Z0-9]{6,30}$#',
                'message' => 'Пароль указан неверно (разрешены только латинские буквы и цифры от 6 до 30 символов',

            ],
        ];
        // Перебор входных данных и сравнение их с паттернами валидации
        foreach ($input as $val) {
            if (!isset($post[$val]) or !preg_match($rules[$val]['pattern'], $post[$val])) {
                $this->error = $rules[$val]['message'];
                return false;
            }
        }
        if (isset($post['ref'])) {
            if ($post['login'] == $post['ref']) {
                return false;
                $this->error = 'Регистрация не возможна';
            }
        }
        return true;
    }

    // Проверка существования Email'a
    public function checkEmailExists($email)
    {
        $params = [
            'email' => $email,
        ];

        return $this->db->column('SELECT id FROM accounts WHERE email = :email', $params);
    }

    // Проверка существования логина
    public function checkLoginExists($login)
    {
        $params = [
            'login' => $login,
        ];

        if ($this->db->column('SELECT id FROM accounts WHERE login = :login', $params)) {
            $this->error = 'Введённый логин уже используется';
            return false;
        }
        return true;
    }

    // Проверка существования Email
    public function checkTokenExists($token)
    {
        $params = [
            'token' => $token,
        ];

        return $this->db->column('SELECT id FROM accounts WHERE token = :token', $params);
    }

    // Проверка существования токена
    public function checkRefExists($login)
    {
        $params = [
            'login' => $login,
        ];

        return $this->db->column('SELECT id FROM accounts WHERE login = :login', $params);

    }

    // Активация аккаунта
    public function activate($token)
    {
        $params = [
            'token' => $token,
        ];

        $this->db->query('UPDATE accounts SET status = 1, token = "" WHERE token = :token', $params);
    }

    // Генерация случайного токена для потверждение регистрации
    public function createToken()
    {
        return substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyz', 30)), 0, 30);
    }

    // Регистрация пользователя в базе данных
    public function register($post)
    {
        $token = $this->createToken();
        if ($post['ref'] == 'none') {
            $ref = 0;
        } else {
            $ref = $this->checkRefExists($post['ref']);
            if (!$ref)
                $ref = 0;
        }
        $params = [
            'id' => '',
            'email' => $post['email'],
            'login' => $post['login'],
            'wallet' => $post['wallet'],
            'password' => password_hash($post['password'], PASSWORD_BCRYPT),
            'ref' => $ref,
            'refBalance' => 0,
            'token' => $token,
            'status' => 0,
        ];
        $this->db->query('INSERT INTO accounts VALUES (:id, :email, :login, :wallet, :password, :ref, :refBalance, :token, :status)', $params);
        mail($post['email'], 'Register', 'Confirm: ' . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/account/confirm/' . $token);
        //$this->db->query('INSERT INTO accounts VALUES(:id, :email, :login, :wallet, :password, :ref, :token, :status)',$params);
        //mail($post['email'], 'Register', 'Confirm: http://localhost/account/confirm/'.$token);
    }

    // Проверка данных для логина
    public function checkData($login, $password)
    {
        $params = [
            'login' => $login,
        ];
        $hash = $this->db->column('SELECT password FROM accounts WHERE login = :login', $params);
        if (!$hash or !password_verify($password, $hash)) {
            return false;
        }
        return true;
    }

    // Проверка верефикации пользователя для логина и сброса пароля
    public function checkStatus($type, $data)
    {
        $params = [
            $type => $data,
        ];

        $status = $this->db->column('SELECT status FROM accounts WHERE ' . $type . ' = ' . $type, $params);
        if ($status != 1) {
            $this->error = "Аккаунт ожидает потверждения по Email";
            return false;
        }
        return true;
    }

    // Вход в аккаунт
    public function login($login)
    {
        $params = [
            'login' => $login,
        ];

        $data = $this->db->row('SELECT * from accounts WHERE login =:login', $params);
        $_SESSION['account'] = $data[0];

    }


    // Запрос на востановление пароля
    public function recovery($post)
    {
        $token = $this->createToken();
        $params = [
            'email' => $post['email'],
            'token' => $token,
        ];
        $this->db->query('UPDATE accounts SET token = :token WHERE email = :email', $params);
        mail($post['email'], 'Recovery', 'Confirm: ' . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/account/reset/' . $token);
    }


    // Сброс пароля
    public function reset($token)
    {
        $new_password = $this->createToken();
        $params = [
            'token' => $token,
            'password' => password_hash($new_password, PASSWORD_BCRYPT),
        ];
        $this->db->query('UPDATE accounts SET status = 1, token = "", password = :password WHERE token = :token', $params);
        return $new_password;
    }

    // Сохранение изменений в профиле
    public function save($post)
    {
        $params = [
            'id' => $_SESSION['account']['id'],
            'email' => $post['email'],
            'wallet' => $post['wallet'],
        ];
        if (!empty($post['password'])) {
            $params['password'] = password_hash($post['password'], PASSWORD_BCRYPT);
            $sql = ',password = :password';
        } else {
            $sql = '';
        }
        foreach ($params as $key => $val) {
            $_SESSION['account'][$key] = $val;
        }
        $this->db->query('UPDATE accounts SET email = :email, wallet = :wallet' . $sql . ' WHERE id = :id', $params);
    }

}