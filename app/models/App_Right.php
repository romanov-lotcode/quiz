<?php

/**
 * Class App_Right
 * Права программы
 */
class App_Right
{
    /*****************************************************
     ******************** ПОЛЯ КЛАССА ********************
     *****************************************************/

    /*****************************************************
     ******************* МЕТОДЫ КЛАССА *******************
     *****************************************************/

    /**
     * Возвращает все права программы
     * @return array
     */
    public static function getAppRights()
    {
        $sql = 'SELECT * FROM app_right WHERE flag >= 0';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->execute();
        // Получение и возврат результатов
        $rights = [];
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $rights[$i] = $row;
            $i++;
        }
        return $rights;
    }
}