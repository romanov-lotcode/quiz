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

// Состояния в проекте по статусу
define('STATE_ON', 1);

// Кодировка со строчной буквы
define('DEFAULT_ENCODING_LOWERCASE', 'utf8');
// Кодировка с прописной буквы
define('DEFAULT_ENCODING_UPPERCASE', 'UTF-8');

// Типы сообщений для пользователя
define('MESSAGE_TYPE_ERROR', 'error'); // Ошибка

// Права пользователя
define('CAN_MODERATOR_DIRECTION', 'CAN_MODERATOR_DIRECTION'); // Может изменять Направление
define('CAN_MODERATOR_TEST', 'CAN_MODERATOR_TEST'); // Может изменять Тест
define('CAN_MODERATOR_TESTING', 'CAN_MODERATOR_TESTING'); // Может изменять Тестирование