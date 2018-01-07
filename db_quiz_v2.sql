-- phpMyAdmin SQL Dump
-- version 4.4.15.5
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Янв 07 2018 г., 18:26
-- Версия сервера: 5.7.11
-- Версия PHP: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `db_quiz_v2`
--

-- --------------------------------------------------------

--
-- Структура таблицы `answer`
--

CREATE TABLE IF NOT EXISTS `answer` (
  `id` int(11) NOT NULL,
  `name` varchar(1024) NOT NULL,
  `question_id` int(11) NOT NULL,
  `complexity_coefficient` int(1) NOT NULL DEFAULT '0',
  `change_user_id` int(11) NOT NULL,
  `change_datetime` datetime NOT NULL,
  `flag` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `answer`
--

INSERT INTO `answer` (`id`, `name`, `question_id`, `complexity_coefficient`, `change_user_id`, `change_datetime`, `flag`) VALUES
(1, 'Правильный', 1, 10, 1, '2017-08-01 00:00:00', 1),
(2, 'Не правильный', 1, 0, 1, '2017-08-25 15:04:41', 1),
(3, 'Тоже неправильный ответ', 1, 0, 1, '2017-08-25 14:37:14', 1),
(4, 'удалить', 1, 3, 1, '2017-08-30 13:55:40', -1),
(5, 'Правильный', 12, 5, 1, '2017-10-27 08:35:28', 1),
(6, 'Неправильный', 12, -5, 1, '2017-10-27 08:35:35', 1),
(7, 'Положительный', 13, 10, 1, '2017-10-27 08:33:05', 1),
(8, 'Отрицательный 1', 13, 0, 1, '2017-10-27 08:33:26', 1),
(9, 'Отрицательный 2', 13, 0, 1, '2017-10-27 08:33:38', 1),
(10, 'Правильный 2', 12, 5, 1, '2017-10-27 08:35:23', 1),
(11, 'Не правильный 2', 12, -5, 1, '2017-10-27 08:35:14', 1),
(12, 'Audi', 2, 10, 1, '2017-10-27 08:43:26', 1),
(13, 'BMW', 2, 0, 1, '2017-10-27 08:43:38', 1),
(14, 'ВАЗ', 2, 0, 1, '2017-10-27 08:43:49', 1),
(15, 'Datsun', 2, 0, 1, '2017-10-27 08:44:16', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `app_right`
--

CREATE TABLE IF NOT EXISTS `app_right` (
  `id` int(11) NOT NULL,
  `right_value` int(2) NOT NULL,
  `right_name` varchar(64) NOT NULL COMMENT 'Наименование правила',
  `description` varchar(512) DEFAULT NULL COMMENT 'Описание',
  `flag` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `app_right`
--

INSERT INTO `app_right` (`id`, `right_value`, `right_name`, `description`, `flag`) VALUES
(1, 1, 'CAN_TESTING_PASS', 'Может проходить тест', 1),
(2, 2, 'CAN_RESULT_VIEW', 'Может просматривать результат', 1),
(3, 4, 'CAN_VIEW_CORRECT_ANSWER', 'Может просматривать правильные ответы', 1),
(4, 8, 'CAN_MODERATOR', 'Обладает правами модератора', 1),
(5, 16, 'CAN_MODERATOR_TEST', 'Может работать с тестами', 1),
(6, 32, 'CAN_MODERATOR_DIRECTION', 'Может работать с направлениями', 1),
(7, 64, 'CAN_MODERATOR_TESTING', 'Может работать с тестированиями', 1),
(8, 128, 'CAN_MODERATOR_QUESTION', 'Может работать с вопросами', 1),
(9, 256, 'CAN_MODERATOR_ANSWER', 'Может работать с ответами', 1),
(10, 512, 'CAN_MODERATOR_USER_GROUP', 'Может работать с группами пользователей', 1),
(11, 1024, 'CAN_MODERATOR_USER', 'Может работать с пользователями', 1),
(12, 2048, 'CAN_MODERATOR_USER_TESTING', 'Может назначать прохождения тестирований', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `direction`
--

CREATE TABLE IF NOT EXISTS `direction` (
  `id` int(11) NOT NULL,
  `name` varchar(1024) NOT NULL,
  `change_user_id` int(11) DEFAULT NULL,
  `change_datetime` datetime DEFAULT NULL,
  `flag` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `direction`
--

INSERT INTO `direction` (`id`, `name`, `change_user_id`, `change_datetime`, `flag`) VALUES
(1, 'Направление 1', 1, '2017-08-09 08:00:47', 0),
(2, 'А12', 1, '2017-08-09 16:27:14', 1),
(3, 'Б2', 1, '2017-08-09 16:08:53', 1),
(4, 'В3', 1, '2017-08-09 16:10:44', 2),
(5, 'Направление 5', 1, '2017-08-30 13:57:45', -1),
(6, 'А222', 1, '2017-08-09 16:27:38', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `menu_panel`
--

CREATE TABLE IF NOT EXISTS `menu_panel` (
  `id` int(11) NOT NULL,
  `name` varchar(512) DEFAULT NULL COMMENT 'Имя ссылки',
  `page_name` varchar(32) NOT NULL DEFAULT 'index',
  `icon_name` varchar(128) NOT NULL DEFAULT 'circle',
  `title` varchar(64) DEFAULT NULL COMMENT 'Титул',
  `description` varchar(256) DEFAULT NULL COMMENT 'Описание',
  `type` int(1) NOT NULL DEFAULT '1' COMMENT 'Тип',
  `url_address` varchar(512) NOT NULL DEFAULT '#' COMMENT 'URL адрес',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Id родителя',
  `index_number` int(1) NOT NULL DEFAULT '0' COMMENT 'Порядковый номер',
  `member` int(1) NOT NULL DEFAULT '0' COMMENT 'Член группы',
  `flag` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `menu_panel`
--

INSERT INTO `menu_panel` (`id`, `name`, `page_name`, `icon_name`, `title`, `description`, `type`, `url_address`, `parent_id`, `index_number`, `member`, `flag`) VALUES
(1, 'Пройти тестирование', 'index', 'home', 'Пройти тестирование', 'Страница для прохождения тестирования', 1, '/main/index', 0, 0, 1, 1),
(2, 'Результаты', 'result', 'circle', 'Результаты пройденных тестов', NULL, 1, '/result/index', 0, 1, 2, 1),
(3, 'Модератор', 'moderator', 'gear', 'Настройки модератора', 'Модератор обладает правами настройки тестов, направлений, тестирований и т.п.', 2, '#', 0, 2, 8, 1),
(4, 'Тест', 'moderator', 'gear', 'Настроить тест', 'Настройки тестов', 1, '/test/index', 3, 2, 16, 1),
(5, 'Направление', 'moderator', 'gear', 'Настроить направление', 'Настройка направлений', 1, '/direction/index', 3, 1, 32, 1),
(6, 'Назначить тестирование', 'moderator', 'gear', 'Назначить тестирование пользователю', 'Назначения тестирований', 1, '/user_testing/index', 3, 4, 2048, 1),
(7, 'Группа пользователей', 'moderator', 'gear', 'Настроить группу пользователей', 'Настройки групп пользователей', 1, '/user_group/index', 3, 3, 512, 1),
(8, 'Пользователь', 'moderator', 'gear', 'Настроить пользователя', 'Настройки пользователей', 1, '/user/index', 3, 5, 1024, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `question`
--

CREATE TABLE IF NOT EXISTS `question` (
  `id` int(11) NOT NULL,
  `name` varchar(2048) NOT NULL,
  `number` int(1) DEFAULT '0',
  `question_type_id` int(1) NOT NULL DEFAULT '0',
  `explanation` varchar(2048) DEFAULT NULL,
  `comment` varchar(2048) DEFAULT NULL,
  `test_id` int(11) NOT NULL,
  `path_img` varchar(1024) DEFAULT NULL,
  `question_time` time NOT NULL,
  `question_time_flag` int(1) NOT NULL,
  `change_user_id` int(11) NOT NULL,
  `change_datetime` datetime NOT NULL,
  `flag` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `question`
--

INSERT INTO `question` (`id`, `name`, `number`, `question_type_id`, `explanation`, `comment`, `test_id`, `path_img`, `question_time`, `question_time_flag`, `change_user_id`, `change_datetime`, `flag`) VALUES
(1, 'Данный вопрос будет иметь длинную надпись, для того, чтобы проверить на сколько симпатично будет он выглядеть при выводе его на дисплей пользователю при его тестировании в автоматизированной системе тестирования. Ну а в конце данного вопроса будет добавлен вопросительный знак?', 1, 0, '', '', 1, '', '00:00:00', 2, 1, '2017-10-27 10:50:10', 1),
(2, 'Какая машина изображена на рисунке?', 3, 0, 'На изображении изображена Audi A4', '', 1, '2.jpg', '00:00:00', 2, 1, '2017-10-27 08:43:02', 1),
(8, 'Удалить', 0, 0, '', '', 1, '8.jpg', '00:00:00', 2, 1, '2017-08-30 13:59:44', -1),
(12, '22244', 2, 1, '1', '2', 1, '', '00:01:00', 2, 1, '2017-10-31 14:32:16', 1),
(13, 'Вопрос', 0, 0, '', '', 1, '', '00:20:00', 1, 1, '2017-10-23 15:24:33', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `question_type`
--

CREATE TABLE IF NOT EXISTS `question_type` (
  `id` int(1) NOT NULL,
  `name` varchar(256) NOT NULL,
  `comment` varchar(512) DEFAULT NULL,
  `flag` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `question_type`
--

INSERT INTO `question_type` (`id`, `name`, `comment`, `flag`) VALUES
(0, 'Один к одному', 'Один вопрос имеет один ответ', 0),
(1, 'Один ко многим', 'Один вопрос может иметь несколько ответов', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `test`
--

CREATE TABLE IF NOT EXISTS `test` (
  `id` int(11) NOT NULL,
  `name` varchar(1024) NOT NULL,
  `comment` varchar(2048) DEFAULT NULL,
  `direction_id` int(11) NOT NULL,
  `change_user_id` int(11) DEFAULT NULL,
  `change_datetime` datetime DEFAULT NULL,
  `flag` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `test`
--

INSERT INTO `test` (`id`, `name`, `comment`, `direction_id`, `change_user_id`, `change_datetime`, `flag`) VALUES
(1, 'Тест1', '32', 1, 0, NULL, 1),
(2, 'Тест для А12', 'Проба', 1, 1, '2017-08-14 16:51:05', 1),
(3, 'Тест2', 'Какой-то коммент', 1, 1, '2017-08-14 15:25:03', 2),
(4, 'Удалите меня1', 'тухлый комментарий', 2, 1, '2017-08-15 09:20:39', 1),
(5, 'Тест "ааа"', '', 1, 1, '2017-08-30 14:01:40', -1);

-- --------------------------------------------------------

--
-- Структура таблицы `testing`
--

CREATE TABLE IF NOT EXISTS `testing` (
  `id` int(11) NOT NULL,
  `name` varchar(1024) NOT NULL,
  `test_id` int(11) NOT NULL,
  `testing_count` int(1) NOT NULL DEFAULT '1',
  `question_count` int(1) NOT NULL DEFAULT '1',
  `is_question_random` int(1) NOT NULL DEFAULT '1',
  `is_answer_random` int(1) NOT NULL DEFAULT '1',
  `minimum_score` int(1) NOT NULL,
  `testing_time` time NOT NULL,
  `testing_time_flag` int(1) NOT NULL,
  `change_user_id` int(11) NOT NULL,
  `change_datetime` datetime NOT NULL,
  `flag` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `testing`
--

INSERT INTO `testing` (`id`, `name`, `test_id`, `testing_count`, `question_count`, `is_question_random`, `is_answer_random`, `minimum_score`, `testing_time`, `testing_time_flag`, `change_user_id`, `change_datetime`, `flag`) VALUES
(1, 'Тестирование 1', 1, 1, 100, 1, 2, 49, '00:11:00', 2, 1, '2017-08-18 11:59:12', 2),
(2, '22', 1, 5, 3, 2, 1, 12, '111:00:00', 1, 1, '2017-08-30 14:03:10', -1),
(3, 'Тестирование2', 1, 30, 4, 1, 1, 30, '00:02:00', 1, 1, '2018-01-07 18:19:17', 1),
(4, 'Тестирование А12', 2, 1, 20, 2, 2, 100, '00:00:50', 2, 1, '2017-08-17 15:56:21', 2),
(5, 'Тестирование3', 1, 30, 3, 1, 1, 10, '00:10:00', 2, 1, '2017-08-18 08:16:51', 1),
(6, 'Тестирование4', 1, 4, 40, 1, 2, 20, '543:10:00', 1, 1, '2017-08-18 08:59:53', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `testing_result`
--

CREATE TABLE IF NOT EXISTS `testing_result` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `testing_id` int(11) NOT NULL,
  `user_group_id` int(11) NOT NULL DEFAULT '0',
  `begin_datetime` datetime DEFAULT NULL,
  `end_datetime` datetime DEFAULT NULL,
  `change_user_id` int(11) NOT NULL DEFAULT '0',
  `change_datetime` datetime DEFAULT NULL,
  `flag` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `testing_result`
--

INSERT INTO `testing_result` (`id`, `user_id`, `testing_id`, `user_group_id`, `begin_datetime`, `end_datetime`, `change_user_id`, `change_datetime`, `flag`) VALUES
(1, 1, 3, 1, '2017-10-23 16:31:58', NULL, 0, NULL, 1),
(2, 1, 3, 1, '2017-10-23 16:50:01', NULL, 0, NULL, 1),
(3, 1, 3, 1, '2017-10-23 16:50:03', NULL, 0, NULL, 1),
(4, 1, 3, 1, '2017-10-23 16:50:04', NULL, 0, NULL, 1),
(5, 1, 3, 1, '2017-10-23 16:50:06', NULL, 0, NULL, 1),
(6, 1, 3, 1, '2017-10-23 16:50:07', NULL, 0, NULL, 1),
(7, 1, 3, 1, '2017-10-23 16:53:21', NULL, 0, NULL, 1),
(8, 1, 3, 1, '2017-10-24 07:52:51', NULL, 0, NULL, 1),
(9, 1, 3, 1, '2017-10-24 08:19:16', NULL, 0, NULL, 1),
(10, 1, 3, 1, '2017-10-24 09:56:22', NULL, 0, NULL, 1),
(11, 1, 3, 1, '2017-10-24 09:56:22', NULL, 0, NULL, 1),
(12, 1, 3, 1, '2017-10-27 13:59:37', NULL, 0, NULL, 1),
(13, 1, 3, 1, '2017-10-30 09:54:30', NULL, 0, NULL, 1),
(14, 1, 3, 1, '2017-10-30 15:40:26', NULL, 0, NULL, 1),
(15, 1, 3, 1, '2017-11-01 08:34:39', NULL, 0, NULL, 1),
(16, 1, 3, 1, '2017-11-01 16:16:05', NULL, 0, NULL, 1),
(17, 1, 3, 1, '2017-11-02 15:50:09', NULL, 0, NULL, 1),
(18, 1, 3, 1, '2017-11-03 09:15:52', NULL, 0, NULL, 1),
(19, 1, 3, 1, '2018-01-05 21:57:03', NULL, 0, NULL, 1),
(20, 1, 3, 1, '2018-01-06 11:56:30', NULL, 0, NULL, 1),
(21, 1, 3, 1, '2018-01-06 12:08:20', NULL, 0, NULL, 1),
(22, 1, 3, 1, '2018-01-06 12:12:15', NULL, 0, NULL, 1),
(23, 1, 3, 1, '2018-01-07 00:48:50', NULL, 0, NULL, 1),
(24, 1, 3, 1, '2018-01-07 16:20:43', '2018-01-07 16:21:12', 0, NULL, 1),
(25, 1, 3, 1, '2018-01-07 16:31:15', '2018-01-07 16:31:29', 0, NULL, 1),
(26, 1, 3, 1, '2018-01-07 18:19:31', '2018-01-07 18:21:32', 0, NULL, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `testing_result_report`
--

CREATE TABLE IF NOT EXISTS `testing_result_report` (
  `id` int(11) NOT NULL,
  `testing_result_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer_id` int(11) NOT NULL,
  `question_time` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `testing_result_report`
--

INSERT INTO `testing_result_report` (`id`, `testing_result_id`, `question_id`, `answer_id`, `question_time`) VALUES
(1, 24, 1, 1, 4),
(2, 24, 13, 7, 3),
(3, 24, 12, 6, 4),
(4, 24, 12, 10, 4),
(5, 24, 2, 12, 17),
(6, 25, 2, 12, 4),
(7, 25, 12, 5, 4),
(8, 25, 13, 8, 3),
(9, 25, 1, 2, 3),
(10, 26, 2, 12, 75),
(11, 26, 13, 7, 6),
(12, 26, 1, 0, 0),
(13, 26, 12, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `lastname` varchar(128) NOT NULL,
  `firstname` varchar(64) NOT NULL,
  `middlename` varchar(128) DEFAULT NULL,
  `login` varchar(32) NOT NULL,
  `password` varchar(256) NOT NULL,
  `email` varchar(128) DEFAULT NULL,
  `registered_datetime` datetime NOT NULL,
  `last_test_datetime` datetime DEFAULT NULL,
  `change_datetime` datetime DEFAULT NULL,
  `change_user_id` int(11) DEFAULT NULL,
  `flag` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `lastname`, `firstname`, `middlename`, `login`, `password`, `email`, `registered_datetime`, `last_test_datetime`, `change_datetime`, `change_user_id`, `flag`) VALUES
(0, 'Нет', '', NULL, 'нет', '123456', NULL, '2017-08-01 00:00:00', NULL, NULL, 0, 0),
(1, 'Романов', 'Сергей', 'Сергеевич', 'romanovss', 'dca20cd83717c9596b17e66822f7f507', '', '2017-05-13 22:32:42', '2018-01-07 18:21:32', '2017-09-01 12:07:02', 1, 1),
(2, 'Романов', 'Сергей', 'По Умолчанию', 'defromanov', 'dca20cd83717c9596b17e66822f7f507', 's.nichipurenko@yandex.ru', '2017-05-31 18:39:03', NULL, NULL, 0, 1),
(3, 'Аакин', 'Серж', '', 'serjserj', 'dca20cd83717c9596b17e66822f7f507', '', '2017-08-21 09:15:46', NULL, NULL, 0, 1),
(4, 'Можно', 'Будет', 'Удалить', 'deletyaev', 'e10adc3949ba59abbe56e057f20f883e', '', '2017-08-30 16:36:42', NULL, '2017-09-01 13:53:03', 1, 1),
(5, 'Куку-Ку', 'Леня1', 'Фыв', 'nikoss', 'da4665ee27bcfb399a74e0ceb48f48ed', '', '2017-09-01 08:34:39', NULL, '2017-09-01 10:45:35', 1, -1),
(6, 'Не', 'Забудь', 'Удалить', 'deletyaev1', 'e10adc3949ba59abbe56e057f20f883e', '', '2017-09-01 08:36:09', NULL, '2017-09-01 10:45:04', 1, -1);

-- --------------------------------------------------------

--
-- Структура таблицы `user_group`
--

CREATE TABLE IF NOT EXISTS `user_group` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `change_datetime` datetime NOT NULL,
  `change_user_id` int(11) NOT NULL,
  `flag` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_group`
--

INSERT INTO `user_group` (`id`, `name`, `change_datetime`, `change_user_id`, `flag`) VALUES
(0, 'Нет', '2017-08-01 00:00:00', 1, 0),
(1, 'Группа1', '2017-08-24 00:00:00', 1, 0),
(2, 'Группа2', '2017-08-02 00:00:00', 1, 1),
(3, 'Группа3', '2017-08-30 13:10:22', 1, 1),
(4, 'Группа4', '2017-08-30 13:47:11', 1, 2),
(5, 'Удалите меня', '2017-08-30 13:48:29', 1, -1);

-- --------------------------------------------------------

--
-- Структура таблицы `user_or_app_right`
--

CREATE TABLE IF NOT EXISTS `user_or_app_right` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `app_right_id` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_or_app_right`
--

INSERT INTO `user_or_app_right` (`id`, `user_id`, `app_right_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 4),
(4, 1, 5),
(5, 1, 6),
(6, 2, 1),
(7, 2, 2),
(8, 1, 7),
(9, 1, 8),
(10, 1, 9),
(11, 3, 1),
(12, 3, 2),
(13, 1, 10),
(14, 4, 1),
(15, 4, 2),
(16, 1, 11),
(17, 5, 1),
(18, 5, 2),
(19, 6, 1),
(20, 6, 2),
(21, 1, 12);

-- --------------------------------------------------------

--
-- Структура таблицы `user_or_testing`
--

CREATE TABLE IF NOT EXISTS `user_or_testing` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `testing_id` int(11) NOT NULL,
  `user_group_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_or_testing`
--

INSERT INTO `user_or_testing` (`id`, `user_id`, `testing_id`, `user_group_id`) VALUES
(7, 2, 4, 1),
(8, 1, 4, 1),
(69, 3, 3, 1),
(70, 4, 3, 1),
(71, 1, 3, 1),
(72, 1, 5, 1),
(73, 2, 5, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `user_or_user_group`
--

CREATE TABLE IF NOT EXISTS `user_or_user_group` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_group_id` int(11) NOT NULL,
  `date_admission` date DEFAULT NULL,
  `date_deduction` date DEFAULT NULL,
  `change_datetime` datetime DEFAULT NULL,
  `change_user_id` int(11) NOT NULL DEFAULT '0',
  `flag` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_or_user_group`
--

INSERT INTO `user_or_user_group` (`id`, `user_id`, `user_group_id`, `date_admission`, `date_deduction`, `change_datetime`, `change_user_id`, `flag`) VALUES
(1, 1, 1, '2017-10-10', '2017-10-12', NULL, 0, 1),
(2, 1, 2, '2017-10-11', '2017-10-11', '2017-10-11 15:08:35', 1, -1),
(3, 2, 1, '2017-10-17', NULL, '2017-10-17 15:14:06', 1, 1),
(4, 3, 1, '2017-10-17', NULL, '2017-10-17 15:14:15', 1, 1),
(5, 4, 1, '2017-10-02', NULL, '2017-10-21 00:00:00', 1, 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `app_right`
--
ALTER TABLE `app_right`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `direction`
--
ALTER TABLE `direction`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `menu_panel`
--
ALTER TABLE `menu_panel`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `question_type`
--
ALTER TABLE `question_type`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `testing`
--
ALTER TABLE `testing`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `testing_result`
--
ALTER TABLE `testing_result`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `testing_result_report`
--
ALTER TABLE `testing_result_report`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- Индексы таблицы `user_group`
--
ALTER TABLE `user_group`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_or_app_right`
--
ALTER TABLE `user_or_app_right`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_or_testing`
--
ALTER TABLE `user_or_testing`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_or_user_group`
--
ALTER TABLE `user_or_user_group`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `answer`
--
ALTER TABLE `answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT для таблицы `app_right`
--
ALTER TABLE `app_right`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT для таблицы `direction`
--
ALTER TABLE `direction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `menu_panel`
--
ALTER TABLE `menu_panel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT для таблицы `question`
--
ALTER TABLE `question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT для таблицы `question_type`
--
ALTER TABLE `question_type`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `test`
--
ALTER TABLE `test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `testing`
--
ALTER TABLE `testing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `testing_result`
--
ALTER TABLE `testing_result`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT для таблицы `testing_result_report`
--
ALTER TABLE `testing_result_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `user_group`
--
ALTER TABLE `user_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `user_or_app_right`
--
ALTER TABLE `user_or_app_right`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT для таблицы `user_or_testing`
--
ALTER TABLE `user_or_testing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=74;
--
-- AUTO_INCREMENT для таблицы `user_or_user_group`
--
ALTER TABLE `user_or_user_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
