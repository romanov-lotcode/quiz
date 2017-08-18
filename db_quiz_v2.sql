-- phpMyAdmin SQL Dump
-- version 4.4.15.5
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Авг 18 2017 г., 16:07
-- Версия сервера: 5.7.11
-- Версия PHP: 5.5.33

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
-- Структура таблицы `app_right`
--

CREATE TABLE IF NOT EXISTS `app_right` (
  `id` int(11) NOT NULL,
  `right_value` int(2) NOT NULL,
  `right_name` varchar(64) NOT NULL COMMENT 'Наименование правила',
  `description` varchar(512) DEFAULT NULL COMMENT 'Описание',
  `flag` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `app_right`
--

INSERT INTO `app_right` (`id`, `right_value`, `right_name`, `description`, `flag`) VALUES
(1, 1, 'CAN_TEST_PASS', 'Может проходить тест', 1),
(2, 2, 'CAN_RESULT_VIEW', 'Может просматривать результат', 1),
(3, 4, 'CAN_VIEW_CORRECT_ANSWER', 'Может просматривать правильные ответы', 1),
(4, 8, 'CAN_MODERATOR', 'Обладает правами модератора', 1),
(5, 16, 'CAN_MODERATOR_TEST', 'Может работать с тестами', 1),
(6, 32, 'CAN_MODERATOR_DIRECTION', 'Может работать с направлениями', 1),
(7, 64, 'CAN_MODERATOR_TESTING', 'Может работать с тестированиями', 1),
(8, 128, 'CAN_MODERATOR_QUESTION', 'Может работать с вопросами', 1);

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
(5, 'Направление 5', 1, '2017-08-09 10:49:05', -1),
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `menu_panel`
--

INSERT INTO `menu_panel` (`id`, `name`, `page_name`, `icon_name`, `title`, `description`, `type`, `url_address`, `parent_id`, `index_number`, `member`, `flag`) VALUES
(1, 'Пройти тестирование', 'index', 'home', 'Пройти тестирование', 'Страница для прохождения тестирования', 1, '/main/index', 0, 0, 1, 1),
(2, 'Результаты', 'result', 'circle', 'Результаты пройденных тестов', NULL, 1, '/main/result', 0, 1, 2, 1),
(3, 'Модератор', 'moderator', 'gear', 'Настройки модератора', 'Модератор обладает правами настройки тестов, направлений, тестирований и т.п.', 2, '#', 0, 2, 8, 1),
(4, 'Тест', 'moderator', 'gear', 'Настроить тест', 'Настройки тестов', 1, '/test/index', 3, 2, 16, 1),
(5, 'Направление', 'moderator', 'gear', 'Настроить направление', 'Настройка направлений', 1, '/direction/index', 3, 1, 32, 1),
(6, 'Тестирование', 'moderator', 'gear', 'Настроить тест', 'Настройки тестирований', 1, '/testing/index', 3, 3, 64, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `question`
--

CREATE TABLE IF NOT EXISTS `question` (
  `id` int(11) NOT NULL,
  `name` varchar(2048) NOT NULL,
  `number` int(1) DEFAULT '0',
  `explanation` varchar(2048) DEFAULT NULL,
  `comment` varchar(2048) DEFAULT NULL,
  `test_id` int(11) NOT NULL,
  `path_img` varchar(1024) DEFAULT NULL,
  `question_type_id` int(1) NOT NULL DEFAULT '0',
  `question_time` time NOT NULL,
  `question_time_flag` int(1) NOT NULL,
  `change_user_id` int(11) NOT NULL,
  `change_datetime` datetime NOT NULL,
  `flag` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `question`
--

INSERT INTO `question` (`id`, `name`, `number`, `explanation`, `comment`, `test_id`, `path_img`, `question_type_id`, `question_time`, `question_time_flag`, `change_user_id`, `change_datetime`, `flag`) VALUES
(1, 'Вопрос А', 1, NULL, NULL, 1, NULL, 0, '00:00:00', 2, 0, '2017-08-18 00:00:00', 1);

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
(0, 'Один к одному', 'Один вопрос имеет один ответ', 1),
(1, 'Один ко многим', 'Один вопрос может иметь несколько ответов', 1);

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
(1, 'Тест1', '32', 1, 0, NULL, 0),
(2, 'Тест для А12', 'Проба', 1, 1, '2017-08-14 16:51:05', 1),
(3, 'Тест2', 'Какой-то коммент', 1, 1, '2017-08-14 15:25:03', 2),
(4, 'Удалите меня1', 'тухлый комментарий', 2, 1, '2017-08-15 09:20:39', 1),
(5, 'Тест "ааа"', '', 1, 1, '2017-08-15 09:59:31', -1);

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
(2, '22', 1, 5, 3, 2, 1, 12, '111:00:00', 1, 1, '2017-08-18 11:51:59', -1),
(3, 'Тестирование2', 1, 3, 5, 2, 1, 30, '00:05:00', 1, 1, '2017-08-17 15:49:32', 1),
(4, 'Тестирование А12', 2, 1, 20, 2, 2, 100, '00:00:50', 2, 1, '2017-08-17 15:56:21', 2),
(5, 'Тестирование3', 1, 30, 3, 1, 1, 10, '00:10:00', 2, 1, '2017-08-18 08:16:51', 1),
(6, 'Тестирование4', 1, 4, 40, 1, 2, 20, '543:10:00', 1, 1, '2017-08-18 08:59:53', 2);

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
  `changed_datetime` datetime DEFAULT NULL,
  `changed_user_id` int(11) DEFAULT NULL,
  `flag` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `lastname`, `firstname`, `middlename`, `login`, `password`, `email`, `registered_datetime`, `last_test_datetime`, `changed_datetime`, `changed_user_id`, `flag`) VALUES
(0, 'Нет', '', NULL, '', '123456', NULL, '2017-08-01 00:00:00', NULL, NULL, NULL, 1),
(1, 'Романов', 'Сергей', 'Сергеевич', 'romanovss', 'dca20cd83717c9596b17e66822f7f507', '', '2017-05-13 22:32:42', NULL, NULL, NULL, 1),
(2, 'Романов', 'Сергей', 'По Умолчанию', 'defromanov', 'dca20cd83717c9596b17e66822f7f507', 's.nichipurenko@yandex.ru', '2017-05-31 18:39:03', NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `user_or_app_right`
--

CREATE TABLE IF NOT EXISTS `user_or_app_right` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `app_right_id` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

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
(9, 1, 8);

--
-- Индексы сохранённых таблиц
--

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
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- Индексы таблицы `user_or_app_right`
--
ALTER TABLE `user_or_app_right`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `app_right`
--
ALTER TABLE `app_right`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT для таблицы `direction`
--
ALTER TABLE `direction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `menu_panel`
--
ALTER TABLE `menu_panel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `question`
--
ALTER TABLE `question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
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
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `user_or_app_right`
--
ALTER TABLE `user_or_app_right`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
