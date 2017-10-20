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
}