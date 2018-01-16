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

    const SHOW_BY_DEFAULT = 20;

    /*****************************************************
     ******************* МЕТОДЫ КЛАССА *******************
     *****************************************************/

    /**
     * Возвращает информацию о результате тестирования
     * @param int $id - ID
     * @param int $user_id - ID пользователя
     * @return bool|mixed
     */
    public static function getTestingResult($id, $user_id)
    {
        $sql = 'SELECT
          testing_result.begin_datetime,
          testing_result.end_datetime,
          testing.name AS testing_name,
          user.lastname,
          user.firstname,
          user.middlename,
          testing.question_count,
          testing.minimum_score,
          testing.is_result_view,
          direction.name AS direction_name
        FROM
          testing_result
          INNER JOIN testing ON (testing_result.testing_id = testing.id)
          INNER JOIN user ON (testing_result.user_id = user.id)
          INNER JOIN test ON (testing.test_id = test.id)
          INNER JOIN direction ON (test.direction_id = direction.id)
        WHERE
          testing_result.id = :id AND
          testing_result.user_id = :user_id AND
          testing_result.flag = 1';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $result->execute();

        // Обращаемся к записи
        $testing_result = $result->fetch(PDO::FETCH_ASSOC);

        if ($testing_result) {
            return $testing_result;
        }
        return false;
    }

    /**
     * Возвращает результаты тестирований по параметрам поиска
     * @param [] $search - Параметры поиска
     * @param int $page - Номер страницы
     * @return array
     */
    public static function getTestingResults($search = null, $page = 1)
    {
        $where = ' WHERE
              (testing_result.flag = 1 OR
              testing_result.flag = 0) AND
              testing_result.user_id = :user_id';
        $page = intval($page);
        if ($page < 1) $page = 1;

        $search['type'] = intval($search['type']);
        if ($search['type'] < 1) $search['type'] = 1;

        if ($search['type'] == 2)
        {

        }
        else
        {
            $where .= ' AND
              testing_result.end_datetime IS NOT NULL';
        }

        $offset = ($page - 1) * self::SHOW_BY_DEFAULT;

        $sql = 'SELECT
              testing_result.id,
              testing.name AS testing_name,
              user_group.name AS user_group_name,
              testing_result.end_datetime,
              testing_result.begin_datetime
            FROM
              testing_result
              INNER JOIN testing ON (testing_result.testing_id = testing.id)
              INNER JOIN user_group ON (testing_result.user_group_id = user_group.id)
            '. $where .' ORDER BY testing_result.end_datetime DESC LIMIT '. self::SHOW_BY_DEFAULT . ' OFFSET '. $offset;

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':user_id', $search['user_id'], PDO::PARAM_INT);
        $result->execute();

        // Получение и возврат результатов
        $testing_result_list = [];
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $testing_result_list[$i] = $row;
            $i++;
        }
        return $testing_result_list;
    }

    /**
     * Возвращет количество записей, удовлетворяющих параметрам поиска
     * @param [] $search - Параметры поиска
     * @return int
     */
    public static function getTotalTestingResults($search = null)
    {
        $where = ' WHERE
              (testing_result.flag = 1 OR
              testing_result.flag = 0) AND
              testing_result.user_id = :user_id';

        $search['type'] = intval($search['type']);
        if ($search['type'] < 1) $search['type'] = 1;

        if ($search['type'] == 2)
        {

        }
        else
        {
            $where .= ' AND
              testing_result.end_datetime IS NOT NULL';
        }

        $sql = 'SELECT
            COUNT(*) AS row_count
          FROM
            testing_result'. $where;
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':user_id', $search['user_id'], PDO::PARAM_INT);
        $result->execute();

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

    /**
     * Удаляет результат тестирования
     * @param int $id - ID результата тестирования
     * @return bool
     */
    public static function delete($id)
    {
        $sql = 'DELETE FROM testing_result WHERE id = :id';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        if ($result->execute())
        {
            return true;
        }
        return false;
    }
}