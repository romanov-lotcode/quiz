-- phpMyAdmin SQL Dump
-- version 4.4.15.5
-- http://www.phpmyadmin.net
--
-- Хост: 10.10.10.155:3306
-- Время создания: Янв 26 2018 г., 08:40
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

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
(12, 2048, 'CAN_MODERATOR_USER_TESTING', 'Может назначать прохождения тестирований', 1),
(13, 4096, 'CAN_OTHER_RESULT_VIEW', 'Может просматривать результат других пользователей', 1),
(14, 8192, 'CAN_MODERATOR_RESULT', 'Может изменять результаты', 1),
(15, 16384, 'CAN_ADMINISTRATOR', 'Обладает правами администратора', 1),
(16, 32768, 'CAN_ADMINISTRATOR_USER_OR_APP_RIGHT', 'Может задавать права пользователям', 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `menu_panel`
--

INSERT INTO `menu_panel` (`id`, `name`, `page_name`, `icon_name`, `title`, `description`, `type`, `url_address`, `parent_id`, `index_number`, `member`, `flag`) VALUES
(1, 'Пройти тестирование', 'index', 'home', 'Пройти тестирование', 'Страница для прохождения тестирования', 1, '/main/index', 0, 0, 1, 1),
(2, 'Результаты', 'result', 'sticky-note', 'Результаты пройденных тестов', NULL, 1, '/result/index', 0, 1, 2, 1),
(3, 'Модератор', 'moderator', 'gear', 'Настройки модератора', 'Модератор обладает правами настройки тестов, направлений, тестирований и т.п.', 2, '#', 0, 2, 8, 1),
(4, 'Тест', 'moderator', 'gear', 'Настроить тест', 'Настройки тестов', 1, '/test/index', 3, 2, 16, 1),
(5, 'Направление', 'moderator', 'gear', 'Настроить направление', 'Настройка направлений', 1, '/direction/index', 3, 1, 32, 1),
(6, 'Назначить тестирование', 'moderator', 'gear', 'Назначить тестирование пользователю', 'Назначения тестирований', 1, '/user_testing/index', 3, 4, 2048, 1),
(7, 'Группа пользователей', 'moderator', 'gear', 'Настроить группу пользователей', 'Настройки групп пользователей', 1, '/user_group/index', 3, 3, 512, 1),
(8, 'Пользователь', 'moderator', 'gear', 'Настроить пользователя', 'Настройки пользователей', 1, '/user/index', 3, 5, 1024, 1),
(9, 'Администратор', 'administrator', 'gears', 'Настройки администратора', 'Администратор может наделить пользователей определенными правами', 2, '#', 0, 3, 16384, 1),
(10, 'Права пользователя', 'administrator', 'gears', 'Права пользователя', 'Необходимо выбрать пользователя, для работы с его правами', 1, '/user_or_app_right/index', 9, 1, 32768, 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `is_result_view` int(1) NOT NULL DEFAULT '1',
  `change_user_id` int(11) NOT NULL,
  `change_datetime` datetime NOT NULL,
  `flag` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `change_user_id` int(11) DEFAULT '0',
  `flag` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `lastname`, `firstname`, `middlename`, `login`, `password`, `email`, `registered_datetime`, `last_test_datetime`, `change_datetime`, `change_user_id`, `flag`) VALUES
(0, 'Нет', '', NULL, 'нет', '123456', NULL, '2017-08-01 00:00:00', NULL, NULL, 0, 0),
(1, 'Романов', 'Сергей', 'Сергеевич', 'romanovss', 'dca20cd83717c9596b17e66822f7f507', '', '2017-05-13 22:32:42', '2018-01-17 14:49:24', '2017-09-01 12:07:02', 1, 0);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_group`
--

INSERT INTO `user_group` (`id`, `name`, `change_datetime`, `change_user_id`, `flag`) VALUES
(0, 'Нет', '2017-08-01 00:00:00', 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `user_or_app_right`
--

CREATE TABLE IF NOT EXISTS `user_or_app_right` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `app_right_id` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_or_app_right`
--

INSERT INTO `user_or_app_right` (`id`, `user_id`, `app_right_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 1, 9),
(10, 1, 10),
(11, 1, 11),
(12, 1, 12),
(13, 1, 13),
(14, 1, 14),
(15, 1, 15),
(16, 1, 16);

-- --------------------------------------------------------

--
-- Структура таблицы `user_or_testing`
--

CREATE TABLE IF NOT EXISTS `user_or_testing` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `testing_id` int(11) NOT NULL,
  `user_group_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `app_right`
--
ALTER TABLE `app_right`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT для таблицы `direction`
--
ALTER TABLE `direction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `menu_panel`
--
ALTER TABLE `menu_panel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT для таблицы `question`
--
ALTER TABLE `question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `question_type`
--
ALTER TABLE `question_type`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `test`
--
ALTER TABLE `test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `testing`
--
ALTER TABLE `testing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `testing_result`
--
ALTER TABLE `testing_result`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `testing_result_report`
--
ALTER TABLE `testing_result_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `user_group`
--
ALTER TABLE `user_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `user_or_app_right`
--
ALTER TABLE `user_or_app_right`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT для таблицы `user_or_testing`
--
ALTER TABLE `user_or_testing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `user_or_user_group`
--
ALTER TABLE `user_or_user_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
