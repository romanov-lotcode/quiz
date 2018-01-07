<?php

/**
 * Class Testing_Result
 * Результаты тестирований
 */
class Testing_Result
{
    /*****************************************************
     ******************** ПОЛЯ КЛАССА ********************
     *****************************************************/

    /*****************************************************
     ******************* МЕТОДЫ КЛАССА *******************
     *****************************************************/

    /**
     * Получить общее количество записей, удовлетворяющих параметрам поиска
     * @param [] $search - Параметры поиска
     * @return int
     */
    public static function getUserTestingCount($search)
    {
        $sql = 'SELECT
            COUNT(*) AS row_count
          FROM
            testing_result
          WHERE
            testing_result.user_id = :user_id AND
            testing_result.testing_id = :testing_id AND
            testing_result.user_group_id = :user_group_id AND
            testing_result.flag = 1';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':user_id', $search['user_id'], PDO::PARAM_INT);
        $result->bindParam(':testing_id', $search['testing_id'], PDO::PARAM_INT);
        $result->bindParam(':user_group_id', $search['user_group_id'], PDO::PARAM_INT);
        $result->execute();
        // Обращаемся к записи
        $count = $result->fetch(PDO::FETCH_ASSOC);

        if ($count) {
            return $count['row_count'];
        }
        return 0;
    }

    /**
     * Добавляет новую запись
     * @param [] $testing_result - Массив с данными
     * @return bool|string
     */
    public static function add($testing_result)
    {
        $sql = 'INSERT INTO testing_result (user_id, testing_id, user_group_id, begin_datetime, flag)
          VALUES (:user_id, :testing_id, :user_group_id, :begin_datetime, :flag)';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':user_id', $testing_result['user_id'], PDO::PARAM_INT);
        $result->bindParam(':testing_id', $testing_result['testing_id'], PDO::PARAM_INT);
        $result->bindParam(':user_group_id', $testing_result['user_group_id'], PDO::PARAM_INT);
        $result->bindParam(':begin_datetime', $testing_result['begin_datetime'], PDO::PARAM_STR);
        $result->bindParam(':flag', $testing_result['flag'], PDO::PARAM_INT);
        if($result->execute())
        {
            return $db->lastInsertId();
        }
        return false;
    }

    /**
     * Изменяет дату завершения тестирования
     * @param [] $testing_result - Массив с данными
     * @return bool|string
     */
    public static function editEndDateTime($testing_result)
    {
        $sql = 'UPDATE testing_result
          SET
            end_datetime = :end_datetime
          WHERE id = :id AND flag > 0';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $testing_result['id'], PDO::PARAM_INT);
        $result->bindParam(':end_datetime', $testing_result['end_datetime'], PDO::PARAM_STR);
        if($result->execute())
        {
            return $db->lastInsertId();
        }
        return false;
    }
}