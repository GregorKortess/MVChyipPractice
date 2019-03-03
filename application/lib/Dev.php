<?php
// Показ ошибок
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Микро функция для дебага
function debug($str)
{
    echo "<pre>";
    var_dump($str);
    echo "<pre>";
    exit;
}