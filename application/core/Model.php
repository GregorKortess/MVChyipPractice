<?php

namespace application\core;

use application\lib\Db;

abstract class Model
{
    // Подключение к базе данных
    public $db;

    public function __construct()
    {
        $this->db = new Db;
    }
}