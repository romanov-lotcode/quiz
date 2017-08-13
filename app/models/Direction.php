<?php

/**
 * Class Direction
 * Направления тестов
 */
class Direction
{
    /*****************************************************
     ******************** ПОЛЯ КЛАССА ********************
     *****************************************************/

    const SHOW_BY_DEFAULT = 20;

    /*****************************************************
     ******************* МЕТОДЫ КЛАССА *******************
     *****************************************************/

    /**
     * Возвращает запись о направлении по его ID
     * @param int $id - ID направления
     * @return bool|array()
     */
    public static function getDirection($id)
    {
        $id = intval($id);
        $sql = 'SELECT
          direction.id,
          direction.name,
          direction.change_user_id,
          direction.change_datetime,
          direction.flag,
          user.lastname,
          user.firstname,
          user.middlename
        FROM
          direction
          INNER JOIN user ON (direction.change_user_id = user.id)
        WHERE direction.id = :id';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();

        // Обращаемся к записи
        $direction = $result->fetch(PDO::FETCH_ASSOC);

        if ($direction) {
            return $direction;
        }
        return false;
    }

    /**
     * Возвращает направления, удовлетворяющие параметрам поиска
     * @param [] $search - параметры поиска
     * @param int $page - номер страницы
     * @return array
     */
    public static function getDirections($search = null, $page = 1)
    {
        $page = intval($page);
        if ($page < 1) $page = 1;

        $offset = ($page - 1) * self::SHOW_BY_DEFAULT;

        $sql = 'SELECT
            *
          FROM
            direction
          WHERE
            direction.name LIKE ? AND
            direction.flag >= 0 AND
            direction.id > 0
          ORDER BY
            direction.name LIMIT '. self::SHOW_BY_DEFAULT .' OFFSET ' . $offset;

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $search['name'] = '%' . $search['name'] . '%';

        $result->execute([$search['name']]);

        // Получение и возврат результатов
        $directions = [];
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $directions[$i] = $row;
            $i++;
        }
        return $directions;
    }

    /**
     * Возвращет количество записей, удовлетворяющих параметрам поиска
     * @param [] $search - параметры поиска
     * @return int
     */
    public static function getTotalDirections($search = null)
    {
        $search['name'] = '%' . $search['name'] . '%';
        $sql = 'SELECT
            COUNT(*) AS row_count
          FROM
            direction
          WHERE
            direction.name LIKE ? AND
            direction.flag >= 0 AND
            direction.id > 0';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->execute([$search['name']]);
        // Обращаемся к записи
        $count = $result->fetch(PDO::FETCH_ASSOC);

        if ($count) {
            return $count['row_count'];
        }
        return 0;
    }

    /**
     * Получить направления, по статусу
     * @param int $state - Статус (по умолчанию включен)
     * @return array
     */
    public static function getDirectionsByState($state = 1)
    {
        $where = '';
        if ($state == STATE_ON)
        {
            $where = ' WHERE direction.flag = 0 OR
          direction.flag = 1';
        }

        $sql = 'SELECT
          direction.id,
          direction.name,
          direction.flag
        FROM
          direction '.$where;

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->execute();

        // Получение и возврат результатов
        $directions = [];
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $directions[$i] = $row;
            $i++;
        }
        return $directions;
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
     * Добавляет новое направление
     * @param [] $direction - массив с данными
     * @return bool|int
     */
    public static function add($direction)
    {
        $sql = 'INSERT INTO direction (name, change_user_id, change_datetime, flag)
          VALUES (:name, :change_user_id, :change_datetime, :flag)';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':name', $direction['name'], PDO::PARAM_STR);
        $result->bindParam(':change_user_id', $direction['change_user_id'], PDO::PARAM_INT);
        $result->bindParam(':change_datetime', $direction['change_datetime'], PDO::PARAM_STR);
        $result->bindParam(':flag', $direction['flag'], PDO::PARAM_INT);
        if($result->execute())
        {
            return $db->lastInsertId();
        }
        return false;
    }

    /**
     * Изменить запись
     * @param [] $direction - массив с данными
     */
    public static function edit($direction)
    {
        $sql = 'UPDATE direction
          SET name = :name, change_user_id = :change_user_id, change_datetime = :change_datetime,
          flag = :flag
          WHERE id = :id AND flag > 0';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $direction['id'], PDO::PARAM_INT);
        $result->bindParam(':name', $direction['name'], PDO::PARAM_STR);
        $result->bindParam(':change_user_id', $direction['change_user_id'], PDO::PARAM_INT);
        $result->bindParam(':change_datetime', $direction['change_datetime'], PDO::PARAM_STR);
        $result->bindParam(':flag', $direction['flag'], PDO::PARAM_INT);
        $result->execute();
    }

    /**
     * Удалить направление (изменить флаг)
     * @param [] $direction - массив с данными
     */
    public static function delete($direction)
    {
        $sql = 'UPDATE direction
          SET
            change_datetime = :change_datetime, change_user_id = :change_user_id, flag = -1
          WHERE id = :id AND flag > 0';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $direction['id'], PDO::PARAM_INT);
        $result->bindParam(':change_datetime', $direction['change_datetime'], PDO::PARAM_STR);
        $result->bindParam(':change_user_id', $direction['change_user_id'], PDO::PARAM_INT);
        $result->execute();
    }
}