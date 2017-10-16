<?php

/**
 * Class User_User_Group
 * Группы пользователей
 */
class User_User_Group
{
    /*****************************************************
     ******************** ПОЛЯ КЛАССА ********************
     *****************************************************/

    /*****************************************************
     ******************* МЕТОДЫ КЛАССА *******************
     *****************************************************/

    /**
     * Получить информацию о пользователе и его группе
     * @param int $id - ID записи
     * @return bool|mixed
     */
    public static function getUserUserGroup($id)
    {
        $sql = 'SELECT
          user_or_user_group.id,
          user_or_user_group.flag,
          user_or_user_group.change_datetime,
          user_or_user_group.change_user_id,
          user.lastname,
          user.firstname,
          user.middlename,
          user.flag AS user_flag,
          user_group.name,
          user_group.flag AS user_group_flag
        FROM
          user_or_user_group
          INNER JOIN user ON (user_or_user_group.user_id = user.id)
          INNER JOIN user_group ON (user_or_user_group.user_group_id = user_group.id)
        WHERE
          user_or_user_group.id = :id AND
          user_or_user_group.flag = 1 AND
          user.flag >= 0';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();

        // Обращаемся к записи
        $user_group = $result->fetch(PDO::FETCH_ASSOC);

        if ($user_group) {
            return $user_group;
        }
        return false;
    }

    /**
     * Получить данные по параметрам поиска
     * @param [] $search - параметры поиска
     * @return array
     */
    public static function getUserUserGroups($search = null)
    {
        $sql = 'SELECT
              user_or_user_group.id,
              user_or_user_group.user_group_id,
              user_or_user_group.date_admission,
              user_group.name,
              user_or_user_group.flag
            FROM
              user_or_user_group
              INNER JOIN user_group ON (user_or_user_group.user_group_id = user_group.id)
            WHERE
              user_or_user_group.flag >= 0 AND
              user_or_user_group.user_id = ?';

        $db = Database::getConnection();
        $result = $db->prepare($sql);

        $result->execute([$search['uid']]);

        // Получение и возврат результатов
        $user_groups = [];
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user_groups[$i] = $row;
            $i++;
        }
        return $user_groups;
    }

    /**
     * Возвращет количество записей, удовлетворяющих параметрам поиска
     * @param [] $search - параметры поиска
     * @return int
     */
    public static function getTotalUserUserGroups($search = null)
    {
        $search['name'] = '%' . $search['name'] . '%';
        $sql = 'SELECT
            COUNT(*) AS row_count
          FROM
              user_or_user_group
              INNER JOIN user_group ON (user_or_user_group.user_group_id = user_group.id)
            WHERE
              user_or_user_group.flag >= 0 AND
              user_or_user_group.user_id = ?';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->execute([$search['uid']]);
        // Обращаемся к записи
        $count = $result->fetch(PDO::FETCH_ASSOC);

        if ($count) {
            return $count['row_count'];
        }
        return 0;
    }

    /**
     * Добавляет группу для пользователя
     * @param [] $user_user_group - информация о группе и пользователе
     * @return bool|string
     */
    public static function add($user_user_group)
    {
        $sql = 'INSERT INTO user_or_user_group (user_id, user_group_id, date_admission, change_user_id, change_datetime, flag)
          VALUES (:user_id, :user_group_id, :date_admission, :change_user_id, :change_datetime, :flag)';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':user_id', $user_user_group['user_id'], PDO::PARAM_INT);
        $result->bindParam(':user_group_id', $user_user_group['user_group_id'], PDO::PARAM_INT);
        $result->bindParam(':date_admission', $user_user_group['date_admission'], PDO::PARAM_STR);
        $result->bindParam(':change_user_id', $user_user_group['change_user_id'], PDO::PARAM_INT);
        $result->bindParam(':change_datetime', $user_user_group['change_datetime'], PDO::PARAM_STR);
        $result->bindParam(':flag', $user_user_group['flag'], PDO::PARAM_INT);
        if($result->execute())
        {
            return $db->lastInsertId();
        }
        return false;
    }

    /**
     * Удалить запись пользователя и его группы (изменить флаг)
     * @param [] $user_group - массив с данными
     */
    public static function delete($user_group)
    {
        $sql = 'UPDATE user_or_user_group
          SET
            date_deduction = :date_deduction, change_datetime = :change_datetime, change_user_id = :change_user_id, flag = -1
          WHERE id = :id AND flag > 0';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $user_group['id'], PDO::PARAM_INT);
        $result->bindParam(':date_deduction', $user_group['date_deduction'], PDO::PARAM_STR);
        $result->bindParam(':change_datetime', $user_group['change_datetime'], PDO::PARAM_STR);
        $result->bindParam(':change_user_id', $user_group['change_user_id'], PDO::PARAM_INT);
        $result->execute();
    }
}