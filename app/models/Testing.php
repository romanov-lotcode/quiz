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
            (testing.flag = 0 OR
            testing.flag = 1) AND
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
            (testing.flag = 0 OR
            testing.flag = 1) AND
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

}