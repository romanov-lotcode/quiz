<?php

/**
 * Class Test
 * Тесты
 */
class Test
{
    /*****************************************************
     ******************** ПОЛЯ КЛАССА ********************
     *****************************************************/

    const SHOW_BY_DEFAULT = 20;

    /*****************************************************
     ******************* МЕТОДЫ КЛАССА *******************
     *****************************************************/

    /**
     * Возвращает тесты, удовлетворяющие параметрам поиска
     * @param [] $search - параметры поиска
     * @param int $page - номер страницы
     * @return array
     */
    public static function getTests($search = null, $page = 1)
    {
        $page = intval($page);
        if ($page < 1) $page = 1;

        $offset = ($page - 1) * self::SHOW_BY_DEFAULT;

        $sql = 'SELECT
            *
          FROM
            test
          WHERE
            test.direction_id = ? AND
            test.name LIKE ? AND
            test.flag >= 0 AND
            test.id > 0
          ORDER BY
            test.name LIMIT '. self::SHOW_BY_DEFAULT .' OFFSET ' . $offset;

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $search['name'] = '%' . $search['name'] . '%';

        $result->execute([$search['direction_id'], $search['name']]);

        // Получение и возврат результатов
        $tests = [];
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $tests[$i] = $row;
            $i++;
        }
        return $tests;
    }

    /**
     * Возвращет количество записей, удовлетворяющих параметрам поиска
     * @param [] $search - параметры поиска
     * @return int
     */
    public static function getTotalTests($search = null)
    {
        $search['name'] = '%' . $search['name'] . '%';
        $sql = 'SELECT
            COUNT(*) AS row_count
          FROM
            test
          WHERE
            test.direction_id = ? AND
            test.name LIKE ? AND
            test.flag >= 0 AND
            test.id > 0';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->execute([$search['direction_id'], $search['name']]);
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
     * Добавляет новый тест
     * @param [] $test - массив с данными
     * @return bool|int
     */
    public static function add($test)
    {
        $sql = 'INSERT INTO test (name, comment, direction_id, change_user_id, change_datetime, flag)
          VALUES (:name, :comment, :direction_id, :change_user_id, :change_datetime, :flag)';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':name', $test['name'], PDO::PARAM_STR);
        $result->bindParam(':comment', $test['comment'], PDO::PARAM_STR);
        $result->bindParam(':direction_id', $test['direction_id'], PDO::PARAM_INT);
        $result->bindParam(':change_user_id', $test['change_user_id'], PDO::PARAM_INT);
        $result->bindParam(':change_datetime', $test['change_datetime'], PDO::PARAM_STR);
        $result->bindParam(':flag', $test['flag'], PDO::PARAM_INT);
        if($result->execute())
        {
            return $db->lastInsertId();
        }
        return false;
    }

}