<?php

class User
{
    /*****************************************************
     ******************** ПОЛЯ КЛАССА ********************
     *****************************************************/

    const SHOW_BY_DEFAULT = 20;

    /*****************************************************
     ******************* МЕТОДЫ КЛАССА *******************
     *****************************************************/

    /**
     * Получить пользователей, удовлетворяющих параметрам поиска
     * @param [] $search - Параметры поиска
     * @param int $page - Номер страницы
     * @return array
     */
    public static function getUsers($search = null, $page = 1)
    {
        $where = '';
        $segment_count = 0;
        $user = [
            'lastname' => '',
            'firstname' => '',
            'middlename' => '',
            'login' => '',
        ];
        $where = ' WHERE user.id > 0 AND user.flag >= 0';
        if ($search['name'] != null)
        {
            $segments = explode(" ", $search['name']);
            if (count($segments) == 3)
            {
                $segment_count = 3;
                $user['lastname'] = '%' .$segments[0] . '%' ;
                $user['firstname'] = '%' . $segments[1] . '%';
                $user['middlename'] = '%' . $segments[2] . '%';
                $where .= ' AND user.lastname LIKE ? AND user.firstname LIKE ? AND user.middlename LIKE ?';
            }
            if (count($segments) == 2)
            {
                $segment_count = 2;
                $user['lastname'] = '%' . $segments[0] . '%';
                $user['firstname'] = '%' . $segments[1] . '%';
                $where .= ' AND user.lastname LIKE ? AND user.firstname LIKE ?';
            }
            if (count($segments) == 1)
            {
                $segment_count = 1;
                $search['name'] = '%' . $search['name'] . '%';
                $where .= ' AND (user.lastname LIKE ? OR user.firstname LIKE ? OR user.middlename LIKE ? OR user.login LIKE ?)';
            }
        }

        $page = intval($page);
        if ($page < 1) $page = 1;

        $offset = ($page - 1) * self::SHOW_BY_DEFAULT;

        $sql = 'SELECT
          user.id,
          user.lastname,
          user.firstname,
          user.middlename,
          user.login,
          user.email,
          user.registered_datetime,
          user.last_test_datetime,
          user.changed_datetime,
          user.changed_user_id,
          user.flag
        FROM
          user'.$where
        .' ORDER BY
        user.lastname LIMIT '. self::SHOW_BY_DEFAULT .' OFFSET ' . $offset;

        $db = Database::getConnection();
        $result = $db->prepare($sql);

        if ($segment_count == 0)
        {
            $result->execute();
        }
        if ($segment_count == 1)
        {
            $result->execute([$search['name'], $search['name'], $search['name'], $search['name']]);
        }
        if ($segment_count == 2)
        {
            $result->execute([$user['lastname'], $user['firstname']]);
        }
        if ($segment_count == 3)
        {
            $result->execute([$user['lastname'], $user['firstname'], $user['middlename']]);
        }

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
     * Возвращет количество записей, удовлетворяющих параметрам поиска
     * @param [] $search - параметры поиска
     * @return int
     */
    public static function getTotalUsers($search = null)
    {
        $where = '';
        $segment_count = 0;
        $user = [
            'lastname' => '',
            'firstname' => '',
            'middlename' => '',
            'login' => '',
        ];
        $where = ' WHERE user.id > 0 AND user.flag >= 0';
        if ($search['name'] != null)
        {
            $segments = explode(" ", $search['name']);
            if (count($segments) == 3)
            {
                $segment_count = 3;
                $user['lastname'] = '%' .$segments[0] . '%' ;
                $user['firstname'] = '%' . $segments[1] . '%';
                $user['middlename'] = '%' . $segments[2] . '%';
                $where .= ' AND user.lastname LIKE ? AND user.firstname LIKE ? AND user.middlename LIKE ?';
            }
            if (count($segments) == 2)
            {
                $segment_count = 2;
                $user['lastname'] = '%' . $segments[0] . '%';
                $user['firstname'] = '%' . $segments[1] . '%';
                $where .= ' AND user.lastname LIKE ? AND user.firstname LIKE ?';
            }
            if (count($segments) == 1)
            {
                $segment_count = 1;
                $search['name'] = '%' . $search['name'] . '%';
                $where .= ' AND (user.lastname LIKE ? OR user.firstname LIKE ? OR user.middlename LIKE ? OR user.login LIKE ?)';
            }
        }
        $sql = 'SELECT
            COUNT(*) AS row_count
          FROM
            user'. $where;

        $db = Database::getConnection();
        $result = $db->prepare($sql);

        if ($segment_count == 0)
        {
            $result->execute();
        }
        if ($segment_count == 1)
        {
            $result->execute([$search['name'], $search['name'], $search['name'], $search['name']]);
        }
        if ($segment_count == 2)
        {
            $result->execute([$user['lastname'], $user['firstname']]);
        }
        if ($segment_count == 3)
        {
            $result->execute([$user['lastname'], $user['firstname'], $user['middlename']]);
        }
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
     * Запоминает пользователя и время начала сессии.
     * @param $userId int - id пользователя
     */
    public static function auth($userId)
    {
        session_start();
        $_SESSION['user'] = $userId;
        $_SESSION['session_start_time'] = time(); // Начало сессии
    }

    /**
     * Проверяет время начала входа пользователя.
     */
    public static function checkSessionTime()
    {
        $time_now = time();
        $time_limit = 86400;
        if ($time_now > $_SESSION['session_start_time'] +  $time_limit )
        {
            // Отчищаем сессию
            $_SESSION = array();
            session_destroy ();
            // Перенаправляем пользователя на главную страницу
            header("Location: /");
        }
    }

    /**
     * Првоеряет авторизовался ли пользователь.
     * @return bool|int
     */
    public static function checkLogged()
    {
        session_start();
        // Если сессия есть, возвращаем id пользователя
        if(isset($_SESSION['user']))
        {
            return $_SESSION['user'];
        }
        return false;
    }


    public static function checkLogin($value)
    {
        $sql = 'SELECT id FROM user WHERE user.login = :login';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':login', $value, PDO::PARAM_STR);
        $result->execute();

        // Обращаемся к записи
        $user = $result->fetch(PDO::FETCH_ASSOC);

        if($user)
        {
            return false;
        }
        return true;
    }

    /**
     * Завершает сессию.
     */
    public static function logout()
    {
        session_start();
        $u_id = User::checkLogged();
        if ($u_id != false)
        {
            $app_directory = new App_Directory();
            $dir_path = '/temp/users';
            $temp_user_dir = ROOT.$dir_path.'/'.$u_id;
            // Удаляем директорию, если она есть
            $app_directory->removeDirectory($temp_user_dir);
        }
        $_SESSION = array();
        session_destroy ();
        // Перенаправляем пользователя на главную страницу
        header("Location: /main/login");
    }

    /**
     * Добавляет нового пользователя
     * @param array() $user - информация о пользователе
     * @return bool|string
     */
    public static function add($user)
    {
        $sql = 'INSERT INTO user '
            .'(lastname, firstname, middlename, login, password, email, registered_datetime, flag) '
            .'VALUES '
            .'(:lastname, :firstname, :middlename, :login, :password, :email, :registered_datetime, :flag)';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':lastname', $user['lastname'], PDO::PARAM_STR);
        $result->bindParam(':firstname', $user['firstname'], PDO::PARAM_STR);
        $result->bindParam(':middlename', $user['middlename'], PDO::PARAM_STR);
        $result->bindParam(':login', $user['login'], PDO::PARAM_STR);
        $result->bindParam(':password', $user['password'], PDO::PARAM_STR);
        $result->bindParam(':email', $user['email'], PDO::PARAM_STR);
        $result->bindParam(':registered_datetime', $user['registered_datetime'], PDO::PARAM_STR);
        $result->bindParam(':flag', $user['flag'], PDO::PARAM_INT);

        if ($result->execute()) {
            // Если запрос выполенен успешно, возвращаем id добавленной записи
            return $db->lastInsertId();
        }
        return false;
    }

    /**
     * Првоеряет данные пользователя.
     * @param array() $user_data - данные о пользователе
     * @return bool||int
     */
    public static function checkUserData($user_data)
    {
        $sql = 'SELECT id FROM user WHERE login = :login AND password = :password';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':login', $user_data['login'], PDO::PARAM_STR);
        $result->bindParam(':password', $user_data['password'], PDO::PARAM_STR);
        $result->execute();

        // Обращаемся к записи
        $user = $result->fetch(PDO::FETCH_ASSOC);

        if($user)
        {
            return $user['id'];
        }
        return false;
    }




    /****************************************************/
    /********* Методы, связанные с пользователем ********/
    /****************************************************/


    /**
     * Устанавливает право для пользователя
     * @param int $user_id - ID пользователя
     * @param int $right - ID права
     * @return bool|string
     */
    public static function setUserRight($user_id, $right = 1)
    {
        $user_id = intval($user_id);
        $right = intval($right);
        if ($user_id < 1 || $right < 1)
        {
            return false;
        }
        $sql = 'INSERT INTO user_or_app_right
          (user_id, app_right_id) VALUES (:user_id, :right)';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $result->bindParam(':right', $right, PDO::PARAM_INT);


        if ($result->execute()) {
            // Если запрос выполенен успешно, возвращаем id добавленной записи
            return $db->lastInsertId();
        }
        return false;
    }

    /**
     * Устанавливает права пользователя по умолчанию
     * @param int $user_id - ID пользователя
     * @return bool
     */
    public static function setDefaultUserRight($user_id)
    {
        $user_id = intval($user_id);
        if ($user_id < 1)
        {
            return false;
        }
        self::setUserRight($user_id, 1); // Может проходить тест
        self::setUserRight($user_id, 2); // Может просматривать свои результаты
    }

    /**
     * Возвращает права пользователя по его ID
     * @param int $id - ID пользователя
     * @return bool||array()
     */
    public static function getUserRights($id)
    {
        $sql = 'SELECT
          app_right.right_value,
          app_right.right_name,
          user_or_app_right.app_right_id,
          app_right.description,
          app_right.flag
        FROM
          user_or_app_right
          INNER JOIN app_right ON (user_or_app_right.app_right_id = app_right.id)
        WHERE
          user_or_app_right.user_id = :id AND
          app_right.flag > 0';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();

        $user_rights = false;
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user_rights[$i] = $row;
            $i++;
        }
        return $user_rights;
    }

}