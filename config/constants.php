<?php

// Пути проекта
define('APP_VIEWS', ROOT.'/app/views/');
define('APP_TEMPLATES', '/app/templates/');

// Маршруты по умолчанию
define('ROUTE_LOGIN', '/main/login');
define('ROUTE_MAIN', '/');

// Id пользователя
define('USER_ID', User::checkLogged());

// Состояния в проекте, по флагу
define('FLAG_NO_CHANGE', 0);
define('FLAG_ON', 1);
define('FLAG_OFF', 2);

// Ответы проекта Да/Нет
define('APP_YES', 1);
define('APP_NO', 2);

// Состояния в проекте по статусу
define('STATE_ON', 1);

// Кодировка со строчной буквы
define('DEFAULT_ENCODING_LOWERCASE', 'utf8');
// Кодировка с прописной буквы
define('DEFAULT_ENCODING_UPPERCASE', 'UTF-8');

// Типы сообщений для пользователя
define('MESSAGE_TYPE_ERROR', 'error'); // Ошибка
define('MESSAGE_TYPE_SUCCESS', 'success'); // Истина||Успех

// Права пользователя
define('CAN_MODERATOR_DIRECTION', 'CAN_MODERATOR_DIRECTION'); // Может изменять Направление
define('CAN_MODERATOR_TEST', 'CAN_MODERATOR_TEST'); // Может изменять Тест
define('CAN_MODERATOR_TESTING', 'CAN_MODERATOR_TESTING'); // Может изменять Тестирование
define('CAN_MODERATOR_QUESTION', 'CAN_MODERATOR_QUESTION'); // Может изменять Вопрос
define('CAN_MODERATOR_ANSWER', 'CAN_MODERATOR_ANSWER'); // Может изменять Ответ
define('CAN_MODERATOR_USER_GROUP', 'CAN_MODERATOR_USER_GROUP'); // Может изменять Группы пользователей
define('CAN_MODERATOR_USER', 'CAN_MODERATOR_USER'); // Может изменять Пользователей
define('CAN_MODERATOR_USER_TESTING', 'CAN_MODERATOR_USER_TESTING'); // Может назначать тестирования

// Страница, с которой был направлен пользователь
define('PAGE_FROM_USER_INDEX', 'ui');

// Значения ограничений
define('USER_GROUP_COUNT_DEFAULT', 5); // Количество групп по умолчанию
define('USER_GROUP_COUNT_NO_LIMIT', 999999999); // Количество групп без лимита