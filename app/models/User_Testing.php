<?php

/**
 * Class User_Testing
 * Тестирования пользователей
 */
class User_Testing
{
    /*****************************************************
     ******************** ПОЛЯ КЛАССА ********************
     *****************************************************/

    const SHOW_BY_DEFAULT = 20;

    /*****************************************************
     ******************* МЕТОДЫ КЛАССА *******************
     *****************************************************/

    /**
     * Возвращает запись по ID
     * @param int $id - ID
     * @return bool|mixed
     */
    public static function getUserTestingByID($id)
    {
        $sql = 'SELECT * FROM user_or_testing WHERE user_or_testing.id = :id';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();
        // Обращаемся к записи
        $user_or_testing = $result->fetch(PDO::FETCH_ASSOC);

        if ($user_or_testing) {
            return $user_or_testing;
        }
        return false;
    }

    /**
     * Возвращает записи, удовлетворяющие параметрам поиска
     * @param [] $search - Параметры поиска
     * @param int $page - Номер страницы
     * @return array
     */
    public static function getTestingListBySearchParam($search, $page = 1)
    {
        $page = intval($page);
        if ($page < 1) $page = 1;

        $offset = ($page - 1) * self::SHOW_BY_DEFAULT;

        $sql = 'SELECT
              user_group.name AS user_group_name,
              testing.name AS testing_name,
              testing.testing_count,
              user_or_testing.testing_id,
              user_or_testing.id,
              user_or_testing.user_group_id,
              testing.question_count,
              testing.minimum_score,
              testing.testing_time,
              testing.testing_time_flag
            FROM
              user_or_testing
              INNER JOIN testing ON (user_or_testing.testing_id = testing.id)
              INNER JOIN test ON (testing.test_id = test.id)
              INNER JOIN direction ON (test.direction_id = direction.id)
              INNER JOIN user_group ON (user_or_testing.user_group_id = user_group.id)
            WHERE
              (direction.flag = 0 OR
              direction.flag = 1) AND
              (test.flag = 0 OR
              test.flag = 1) AND
              (testing.flag = 0 OR
              testing.flag = 1) AND
              user_or_testing.user_id = ? AND
              test.direction_id = ? AND
              testing.name LIKE ?
            ORDER BY
              testing.name LIMIT '. self::SHOW_BY_DEFAULT .' OFFSET ' . $offset;

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $search['name'] = '%' . $search['name'] . '%';

        $result->execute([$search['user_id'], $search['direction_id'], $search['name']]);

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
     * @param [] $search - Параметры поиска
     * @return int
     */
    public static function getTotalTestingListBySearchParam($search)
    {
        $search['name'] = '%' . $search['name'] . '%';
        $sql = 'SELECT
            COUNT(*) AS row_count
          FROM
            user_or_testing
            INNER JOIN testing ON (user_or_testing.testing_id = testing.id)
            INNER JOIN test ON (testing.test_id = test.id)
            INNER JOIN direction ON (test.direction_id = direction.id)
            INNER JOIN user_group ON (user_or_testing.user_group_id = user_group.id)
          WHERE
            (direction.flag = 0 OR
            direction.flag = 1) AND
            (test.flag = 0 OR
            test.flag = 1) AND
            (testing.flag = 0 OR
            testing.flag = 1) AND
            user_or_testing.user_id = ? AND
            test.direction_id = ? AND
            testing.name LIKE ?';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->execute([$search['user_id'], $search['direction_id'], $search['name']]);
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
     * Возвращает пользователей по тестированию и группе
     * @param [] $search - Параметры поиска
     * @return array
     */
    public static function getUsersByTestingByGroup($search = null)
    {
        $sql = 'SELECT
          user_or_testing.id,
          user_or_testing.user_id
        FROM
          user_or_testing
        WHERE
          user_or_testing.testing_id = :testing_id AND
          user_or_testing.user_group_id = :user_group_id';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':testing_id', $search['testing_id'], PDO::PARAM_INT);
        $result->bindParam(':user_group_id', $search['user_group_id'], PDO::PARAM_INT);
        $result->execute();
        // Получение и возврат результатов
        $users = [];
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $users[$i] = $row;
            $i++;
        }
        return $users;
    }

    /**
     * Удаляет тестирования для пользователей, подходящих по выборке
     * @param [] $users - Массив с пользователями
     * @return bool
     */
    public static function deleteSelected($users)
    {
        $prepared_users = array_combine(
            array_map(function($key) {
                return ':var_'.$key;
            }, array_keys($users)),
            array_values($users)
        );

        $sql = 'DELETE FROM user_or_testing WHERE user_or_testing.id IN ('.implode(',', array_keys($prepared_users)).')';
        $db = Database::getConnection();
        $result = $db->prepare($sql);

        foreach($users as $key => $val) {
            $result->bindParam(':var_'.$key, $val);
        }
        if ($result->execute($prepared_users))
        {
            return true;
        }
        return false;
    }

    /**
     * Добавляет тестирования для пользователей
     * @param [] $users - Массив с пользователями
     * @param [] $search - Параметры поиска
     * @return bool
     */
    public static function addTestsForUsers($users, $search)
    {
        if (is_array($users) && count($users) < 1)
        {
            return false;
        }
        $sql = 'INSERT INTO user_or_testing (user_id, testing_id, user_group_id) VALUES (:user_id, :testing_id, :user_group_id)';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        foreach($users as $key => $value) {
            $result->bindParam(':user_id', $value, PDO::PARAM_INT);
            $result->bindParam(':testing_id', $search['testing_id'], PDO::PARAM_INT);
            $result->bindParam(':user_group_id', $search['user_group_id'], PDO::PARAM_INT);
            $result->execute();
        }
    }
}