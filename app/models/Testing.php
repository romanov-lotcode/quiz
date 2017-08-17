<?php

/**
 * Class Testing
 * Тестирования
 */
class Testing
{
    /*****************************************************
     ******************** ПОЛЯ КЛАССА ********************
     *****************************************************/

    const SHOW_BY_DEFAULT = 20;

    /*****************************************************
     ******************* МЕТОДЫ КЛАССА *******************
     *****************************************************/

    /**
     * @param [] $search - параметры поиска
     * @param int $page - номер страницы
     * @return array
     */
    public static function getTestingList($search, $page)
    {
        $page = intval($page);
        if ($page < 1) $page = 1;

        $offset = ($page - 1) * self::SHOW_BY_DEFAULT;

        $sql = 'SELECT
            testing.id,
            testing.name,
            testing.test_id,
            testing.testing_count,
            testing.question_count,
            testing.is_question_random,
            testing.is_answer_random,
            testing.minimum_score,
            testing.testing_time,
            testing.testing_time_flag,
            testing.change_user_id,
            testing.change_datetime,
            testing.flag
          FROM
            testing
            INNER JOIN test ON (testing.test_id = test.id)
            INNER JOIN direction ON (test.direction_id = direction.id)
          WHERE
            testing.test_id = ? AND
            testing.flag >= 0 AND
            test.direction_id = ? AND
            (test.flag = 0 OR
            test.flag = 1) AND
            (direction.flag = 0 OR
            direction.flag = 1) AND
            testing.name LIKE ?
          ORDER BY
            testing.name LIMIT '. self::SHOW_BY_DEFAULT .' OFFSET ' . $offset;

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $search['name'] = '%' . $search['name'] . '%';

        $result->execute([$search['test_id'], $search['direction_id'], $search['name']]);

        // Получение и возврат результатов
        $testing_list = [];
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $testing_list[$i] = $row;
            $i++;
        }
        return $testing_list;
    }

    /**
     * Возвращет количество записей, удовлетворяющих параметрам поиска
     * @param [] $search - параметры поиска
     * @return int
     */
    public static function getTotalTestingList($search)
    {
        $search['name'] = '%' . $search['name'] . '%';
        $sql = 'SELECT
            COUNT(*) AS row_count
          FROM
            testing
            INNER JOIN test ON (testing.test_id = test.id)
            INNER JOIN direction ON (test.direction_id = direction.id)
          WHERE
            testing.test_id = ? AND
            testing.flag >= 0 AND
            test.direction_id = ? AND
            (test.flag = 0 OR
            test.flag = 1) AND
            (direction.flag = 0 OR
            direction.flag = 1) AND
            testing.name LIKE ?';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->execute([$search['test_id'], $search['direction_id'], $search['name']]);
        // Обращаемся к записи
        $count = $result->fetch(PDO::FETCH_ASSOC);

        if ($count) {
            return $count['row_count'];
        }
        return 0;
    }

    /**
     * Возвращает порядковый номер по номеру страницы
     * @param int $page - номер страницы
     * @return int
     */
    public static function getIndexNumber($page)
    {
        $page = intval($page);
        $result = ($page - 1) * self::SHOW_BY_DEFAULT;
        return $result;
    }

    /**
     * Добавляет новое тестирование
     * @param [] $testing - массив с данными
     * @return bool|int
     */
    public static function add($testing)
    {
        $sql = 'INSERT INTO testing (name, test_id, testing_count, question_count, is_question_random,
            is_answer_random, minimum_score, testing_time, testing_time_flag, change_user_id, change_datetime, flag)
          VALUES (:name, :test_id, :testing_count, :question_count, :is_question_random,
            :is_answer_random, :minimum_score, :testing_time, :testing_time_flag, :change_user_id, :change_datetime, :flag)';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':name', $testing['name'], PDO::PARAM_STR);
        $result->bindParam(':test_id', $testing['test_id'], PDO::PARAM_INT);
        $result->bindParam(':testing_count', $testing['testing_count'], PDO::PARAM_INT);
        $result->bindParam(':question_count', $testing['question_count'], PDO::PARAM_INT);
        $result->bindParam(':is_question_random', $testing['is_question_random'], PDO::PARAM_INT);
        $result->bindParam(':is_answer_random', $testing['is_answer_random'], PDO::PARAM_INT);
        $result->bindParam(':minimum_score', $testing['minimum_score'], PDO::PARAM_INT);
        $result->bindParam(':testing_time', $testing['testing_time'], PDO::PARAM_STR);
        $result->bindParam(':testing_time_flag', $testing['testing_time_flag'], PDO::PARAM_INT);
        $result->bindParam(':change_user_id', $testing['change_user_id'], PDO::PARAM_INT);
        $result->bindParam(':change_datetime', $testing['change_datetime'], PDO::PARAM_STR);
        $result->bindParam(':flag', $testing['flag'], PDO::PARAM_INT);
        if($result->execute())
        {
            return $db->lastInsertId();
        }
        return false;
    }
}