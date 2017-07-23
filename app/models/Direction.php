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

    const SHOW_BY_DEFAULT = 1;

    /*****************************************************
     ******************* МЕТОДЫ КЛАССА *******************
     *****************************************************/

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
}