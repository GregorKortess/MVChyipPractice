<?php

namespace application\core;

class View
{

    public $path;
    public $route;
    public $layout = 'default';

    // Определяем какой вид нужно подключить
    public function __construct($route)
    {
        $this->route = $route;
        $this->path = $route['controller'] . '/' . $route['action'];
    }

    // Загрузка View
    public function render($title, $vars = [])
    {
        extract($vars);
        $path = 'application/views/' . $this->path . '.php';
        if (file_exists($path)) {
            ob_start();
            require $path;
            $content = ob_get_clean();
            require 'application/views/layouts/' . $this->layout . '.php';
        }
    }

    // Переадресация пользователя на определённую страницу
    public function redirect($url)
    {
        header('location:/' . $url);
        exit;
    }

    // Показ ошибок
    public static function errorCode($code)
    {
        http_response_code($code);
        $path = 'application/views/errors/' . $code . '.php';
        if (file_exists($path)) {
            require $path;
        }
        exit;
    }

    // Вывод сообщения на экран
    public function message($status,$message)
    {
        exit(json_encode(['status' => $status,'message' => $message]));
    }

    // Переадресация для Ajax
    public function location($url)
    {
        exit(json_encode(['url' => $url]));

    }
}