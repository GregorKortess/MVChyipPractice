-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Мар 03 2019 г., 14:01
-- Версия сервера: 10.1.37-MariaDB
-- Версия PHP: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `test`
--

-- --------------------------------------------------------

--
-- Структура таблицы `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `login` varchar(15) NOT NULL,
  `wallet` varchar(15) NOT NULL,
  `password` varchar(200) NOT NULL,
  `ref` int(11) NOT NULL,
  `refBalance` float NOT NULL,
  `token` varchar(30) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `accounts`
--

INSERT INTO `accounts` (`id`, `email`, `login`, `wallet`, `password`, `ref`, `refBalance`, `token`, `status`) VALUES
(1, 'CortessHack@gmail.com', 'Cortess', '377731ff', '$2y$10$dNdMA1Ap1YQcHJGryY3BwuIHilFPp5XUdkJLSWX5anmqw8BkFC1Lq', 1, 2500, 'vgr200q7ya8qhns24ee629dl328i8u', 1),
(19, 'cureyano@proeasyweb.com', 'sanya', '377731ff', '$2y$10$9cOZtCuZSk0eLxRV7hgL1.33JKDv9ePhEK5J6XFuHoSBxUrPyjQOy', 1, 0, '', 1),
(17, 'cleril@mail.ru', 'Sobaka', '377731ff', '$2y$10$4R.DehG9IRvTzAmH8rvtBO0.S1UYeRdFjisrfn4KBorgmW.RqwnT.', 1, 0, '', 1),
(18, 'sihola@alpha-web.net', 'kekel', '377731ff', '$2y$10$k9dZ4INyVBqzujfZJR0vAezwyE6VRA7YMuu40Qguxl079g6WJGLba', 1, 0, '', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `unixTime` int(11) NOT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `history`
--

INSERT INTO `history` (`id`, `uid`, `unixTime`, `description`) VALUES
(1, 1, 1551367616, 'тест истории'),
(2, 1, 1551367759, 'Инвестиция под номером #9'),
(3, 1, 1551367946, 'Реферальное вознаграждение  под номером 0'),
(4, 1, 1551367946, 'Инвестиция под номером 10'),
(5, 1, 1551368088, 'Сумма реферального вознаграждения 500₽'),
(6, 1, 1551368088, 'Инвестиция под номером 11'),
(7, 1, 1551561429, 'Вывод средств с рефералов₽'),
(8, 1, 1551562244, 'Вывод реферального вознаграждения, сумма ₽'),
(9, 1, 1551562483, 'Вывод реферального вознаграждения, сумма 2500 ₽'),
(10, 1, 1551562654, 'Выплата реферального вознаграждения произведена, сумма 2500 $');

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `text` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `posts`
--

INSERT INTO `posts` (`id`, `name`, `description`, `text`) VALUES
(72, 'Test', 'Description', 'text , text ,text more text'),
(73, 'Lorem Ipsum', '\"Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...\"', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis varius aliquet eros at faucibus. Nulla sed quam et nulla pharetra viverra non sit amet felis. Nunc vestibulum pellentesque velit, quis eleifend nunc volutpat at. Vestibulum eu ante eget est maximus eleifend. Donec aliquam nulla augue, eu lobortis enim iaculis quis. Donec tellus leo, maximus volutpat mi sit amet, lobortis rhoncus elit. In hac habitasse platea dictumst. Nullam malesuada in quam at bibendum. Fusce urna nisi, fermentum ut maximus quis, varius vel neque. Suspendisse elit nunc, condimentum non metus vitae, euismod porttitor tortor. ');

-- --------------------------------------------------------

--
-- Структура таблицы `ref_withdraw`
--

CREATE TABLE `ref_withdraw` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `unixTime` int(11) NOT NULL,
  `amount` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tariffs`
--

CREATE TABLE `tariffs` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `sumIn` float NOT NULL,
  `sumOut` float NOT NULL,
  `percent` float NOT NULL,
  `unixTimeStart` int(11) NOT NULL,
  `unixTimeFinish` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tariffs`
--

INSERT INTO `tariffs` (`id`, `uid`, `sumIn`, `sumOut`, `percent`, `unixTimeStart`, `unixTimeFinish`) VALUES
(11, 1, 10000, 15000, 50, 1551368088, 1552448088),
(10, 1, 10000, 15000, 50, 1551367946, 1552447946),
(9, 1, 10000, 15000, 50, 1551367759, 1552447759),
(8, 1, 10000, 15000, 50, 1551367616, 1552447616),
(7, 1, 10000, 15000, 50, 1551367090, 1552447090);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `ref_withdraw`
--
ALTER TABLE `ref_withdraw`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tariffs`
--
ALTER TABLE `tariffs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT для таблицы `ref_withdraw`
--
ALTER TABLE `ref_withdraw`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `tariffs`
--
ALTER TABLE `tariffs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
