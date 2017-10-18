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

    /*****************************************************
     ******************* МЕТОДЫ КЛАССА *******************
     *****************************************************/

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