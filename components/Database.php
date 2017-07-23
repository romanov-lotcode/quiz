<?php

/**
 * Компонент для работы с базой данных
 */
class Database
{

    /**
     * Устанавливает соединение с базой данных
     * @return \PDO <p>Объект класса PDO для работы с БД</p>
     */
    public static function getConnection()
    {
        // Получаем параметры подключения из файла
        $paramsPath = ROOT . '/config/db.php';
        $params = include($paramsPath);

        try
        {
            // Устанавливаем соединение
            $dsn = "mysql:host={$params['host']};port={$params['port']};dbname={$params['database']}";
            $db = new PDO($dsn, $params['user'], $params['password']);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Задаем кодировку
            $db->exec('set names utf8');

            return $db;
        }
        catch (PDOException $e)
        {
            $errors['no_connection'] = 'Не удалось подключиться к базе данных.';
            $error_file = ROOT . '/app/views/error/error.php';
            if (file_exists($error_file))
            {
                include_once $error_file;
            }
            else
            {
                echo $errors['no_connection'];
            }
            exit();
        }

    }

}
