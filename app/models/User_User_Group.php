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
}