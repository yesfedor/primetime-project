-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Окт 06 2021 г., 22:55
-- Версия сервера: 5.6.39-83.1
-- Версия PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `cc38255_purplex`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Applications`
--

CREATE TABLE IF NOT EXISTS `Applications` (
  `app_id` int(9) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `owner_uid` int(9) NOT NULL,
  `secure_key` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_key` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` set('enabled','disabled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'disabled',
  `access_permission` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '[]',
  `domain` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect_uri` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dateRegistration` int(10) NOT NULL,
  PRIMARY KEY (`app_id`),
  KEY `owner_uid` (`owner_uid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `Applications`
--

INSERT INTO `Applications` (`app_id`, `title`, `is_verified`, `owner_uid`, `secure_key`, `access_key`, `status`, `access_permission`, `domain`, `redirect_uri`, `dateRegistration`) VALUES
(1, 'Purple X', 1, 1, '20562d0e9406380117edb77cb0e8f3b8.6086aeb20ce4b', '25e1676f52219510a0565e3c83982ebd.6086aeb20ce55', 'enabled', '[]', 'beta.purplex.ru', '/auth', 1633550090);

-- --------------------------------------------------------

--
-- Структура таблицы `Devices`
--

CREATE TABLE IF NOT EXISTS `Devices` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `client_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `app_id` int(9) NOT NULL,
  `platform` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `uid` int(9) DEFAULT NULL,
  `time` int(10) NOT NULL,
  `permission` set('allow','deny') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'allow',
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `uid` int(9) NOT NULL AUTO_INCREMENT,
  `domain` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dateRegistration` int(10) NOT NULL,
  `dateVisit` int(10) NOT NULL,
  `platformVisit` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` set('male','female') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthday` date DEFAULT NULL,
  `avatar` varchar(512) DEFAULT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `access` set('author','developer-verfy','developer-pool','co-author','administrator','tester-verfy','tester-default','moderator','user-verfy','user-default','none') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user-default',
  `blocking` set('none','temporarily','forever','deleted') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `status` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `domain` (`domain`),
  UNIQUE KEY `phone` (`phone`),
  KEY `blocking` (`blocking`),
  KEY `name` (`name`),
  KEY `surname` (`surname`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `User`
--

INSERT INTO `User` (`uid`, `domain`, `password`, `name`, `surname`, `dateRegistration`, `dateVisit`, `platformVisit`, `gender`, `birthday`, `avatar`, `phone`, `email`, `access`, `blocking`, `status`) VALUES
(1, 'yesfedor', 'e8ca3abc1f6117c8358ff36eb64b74c0d48f0fb60cbb61f08095bf04345d6790911bc89c2dde460894d00740fb21ef99', 'Фёдор', 'Гаранин', 1633550090, 1633550090, 'Windows', 'male', '2000-06-15', NULL, NULL, 'yesfedor.go@gmail.com', 'user-default', 'none', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
