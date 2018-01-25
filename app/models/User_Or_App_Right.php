<?php

/**
 * Class User_Or_App_Right
 * Права программы для пользователя
 */
class User_Or_App_Right
{
    /**
     * Добавляет правило для пользователя
     * @param int $user_id - ID пользователя
     * @param int $app_right_id - ID правила
     * @return bool|int
     */
    public static function add($user_id, $app_right_id)
    {
        $sql = 'INSERT INTO user_or_app_right (user_id, app_right_id)
          VALUES (:user_id, :app_right_id)';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $result->bindParam(':app_right_id', $app_right_id, PDO::PARAM_INT);
        if($result->execute())
        {
            return $db->lastInsertId();
        }
        return false;
    }


    /**
     * Удаляет правило для пользователя
     * @param int $user_id - ID пользователя
     * @param int $app_right_id - ID правила
     * @return bool
     */
    public static function delete($user_id, $app_right_id)
    {
        $sql = 'DELETE FROM user_or_app_right WHERE user_id = :user_id AND app_right_id = :app_right_id';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $result->bindParam(':app_right_id', $app_right_id, PDO::PARAM_INT);
        if ($result->execute())
        {
            return true;
        }
        return false;
    }
}