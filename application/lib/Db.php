<?php

namespace application\lib;
use PDO;

class db
{
    protected $db;

    // Подключаем данные из дб и вставляем их в PDO запрос
    public function __construct()
    {
        $config = require 'application/config/db.php';
        $this->db = new PDO('mysql:host='.$config['host'].';dbname='.$config['name'].';charset=utf8;',$config['user'],$config['password']);
    }

    // Функция для передачи запросов в базу данных
    public function query($sql,$params = [])
    {
        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            foreach ($params as $key => $val) {
                if (is_int($val)) {
                    $type = PDO::PARAM_INT;
                } else {
                    $type = PDO::PARAM_STR;
                }
                $stmt->bindValue(':'.$key,$val,$type);
            }
        }
        $stmt->execute();
        return $stmt;
    }

    // Вывод строки из дб
    public function row($sql,$params = [])
    {
        $result = $this->query($sql, $params);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    // Вывод колонны из дб
    public function column($sql,$params = [])
    {
        $result = $this->query($sql,$params);
        return $result->fetchColumn();
    }

    // Последняя добавленная запись
    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }

}