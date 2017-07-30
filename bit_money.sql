-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
<<<<<<< HEAD
-- Время создания: Июл 28 2017 г., 05:17
=======
-- Время создания: Июл 30 2017 г., 17:41
>>>>>>> refs/remotes/origin/main
-- Версия сервера: 5.7.13
-- Версия PHP: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `bit_money`
--

-- --------------------------------------------------------

--
-- Структура таблицы `account`
--

CREATE TABLE IF NOT EXISTS `account` (
  `id` int(10) unsigned NOT NULL,
  `fio` varchar(256) NOT NULL,
  `login` varchar(256) NOT NULL,
  `password` varbinary(256) NOT NULL,
  `balance` decimal(13,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `account`
--

INSERT INTO `account` (`id`, `fio`, `login`, `password`, `balance`) VALUES
<<<<<<< HEAD
(1, 'Сергей Крохмаль', 'sergey', 0xe1ec7b29541f6c0db5b452b6de5fe1c5, '175389.00');
=======
(1, 'Сергей Крохмаль', 'sergey', 0xe1ec7b29541f6c0db5b452b6de5fe1c5, '475.00');
>>>>>>> refs/remotes/origin/main

-- --------------------------------------------------------

--
-- Структура таблицы `transaction`
--

CREATE TABLE IF NOT EXISTS `transaction` (
  `id` int(10) unsigned NOT NULL,
  `id_account` int(10) unsigned NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sum` decimal(13,2) NOT NULL,
  `action` enum('withdraw','refill') NOT NULL
<<<<<<< HEAD
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
=======
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
>>>>>>> refs/remotes/origin/main

--
-- Дамп данных таблицы `transaction`
--

INSERT INTO `transaction` (`id`, `id_account`, `time`, `sum`, `action`) VALUES
<<<<<<< HEAD
(1, 1, '2017-07-27 04:03:37', '10.00', 'withdraw'),
(2, 1, '2017-07-27 04:05:42', '0.01', 'withdraw'),
(3, 1, '2017-07-27 04:48:12', '9.99', 'withdraw'),
(4, 1, '2017-07-27 04:49:55', '90.50', 'withdraw'),
(5, 1, '2017-07-27 04:50:02', '0.50', 'withdraw'),
(6, 1, '2017-07-27 05:26:29', '5000.00', 'withdraw'),
(7, 1, '2017-07-28 05:08:24', '10.00', 'withdraw');
=======
(1, 1, '2017-07-06 12:33:11', '20.00', 'withdraw'),
(2, 1, '2017-07-30 14:37:52', '5.00', 'withdraw');
>>>>>>> refs/remotes/origin/main

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `account`
--
ALTER TABLE `account`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `transaction`
--
ALTER TABLE `transaction`
<<<<<<< HEAD
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
=======
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
>>>>>>> refs/remotes/origin/main
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
