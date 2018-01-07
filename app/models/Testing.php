<?php

/**
 * Class Testing
 * Тестирования
 */
class Testing
{
    /*****************************************************
     ******************** ПОЛЯ КЛАССА ********************
     *****************************************************/

    const SHOW_BY_DEFAULT = 20;

    /*****************************************************
     ******************* МЕТОДЫ КЛАССА *******************
     *****************************************************/

    /**
     * Возвращает запись о тестировании по его ID
     * @param int $id - ID тестирования
     * @return bool|array()
     */
    public static function getTesting($id)
    {
        $id = intval($id);
        $sql = 'SELECT
          testing.id,
          testing.name,
          testing.test_id,
          testing.testing_count,
          testing.question_count,
          testing.is_question_random,
          testing.is_answer_random,
          testing.minimum_score,
          testing.testing_time,
          testing.testing_time_flag,
          testing.change_user_id,
          testing.change_datetime,
          testing.flag,
          user.lastname,
          user.firstname,
          user.middlename,
          test.name AS test_name,
          test.flag AS test_flag
        FROM
          testing
          INNER JOIN user ON (testing.change_user_id = user.id)
          INNER JOIN test ON (testing.test_id = test.id)
        WHERE
          testing.id = :id AND testing.flag >= 0';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();

        // Обращаемся к записи
        $testing = $result->fetch(PDO::FETCH_ASSOC);

        if ($testing) {
            return $testing;
        }
        return false;
    }

    /**
     * @param [] $search - параметры поиска
     * @param int $page - номер страницы
     * @return array
     */
    public static function getTestingList($search, $page)
    {
        $page = intval($page);
        if ($page < 1) $page = 1;

        $offset = ($page - 1) * self::SHOW_BY_DEFAULT;

        $sql = 'SELECT
            testing.id,
            testing.name,
            testing.test_id,
            testing.testing_count,
            testing.question_count,
            testing.is_question_random,
            testing.is_answer_random,
            testing.minimum_score,
            testing.testing_time,
            testing.testing_time_flag,
            testing.change_user_id,
            testing.change_datetime,
            testing.flag
          FROM
            testing
            INNER JOIN test ON (testing.test_id = test.id)
            INNER JOIN direction ON (test.direction_id = direction.id)
          WHERE
            testing.test_id = ? AND
            testing.flag >= 0 AND
            test.direction_id = ? AND
            (test.flag = 0 OR
            test.flag = 1) AND
            (direction.flag = 0 OR
            direction.flag = 1) AND
            testing.name LIKE ?
          ORDER BY
            testing.name LIMIT '. self::SHOW_BY_DEFAULT .' OFFSET ' . $offset;

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $search['name'] = '%' . $search['name'] . '%';

        $result->execute([$search['test_id'], $search['direction_id'], $search['name']]);

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
     * @param [] $search - параметры поиска
     * @return int
     */
    public static function getTotalTestingList($search)
    {
        $search['name'] = '%' . $search['name'] . '%';
        $sql = 'SELECT
            COUNT(*) AS row_count
          FROM
            testing
            INNER JOIN test ON (testing.test_id = test.id)
            INNER JOIN direction ON (test.direction_id = direction.id)
          WHERE
            testing.test_id = ? AND
            testing.flag >= 0 AND
            test.direction_id = ? AND
            (test.flag = 0 OR
            test.flag = 1) AND
            (direction.flag = 0 OR
            direction.flag = 1) AND
            testing.name LIKE ?';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->execute([$search['test_id'], $search['direction_id'], $search['name']]);
        // Обращаемся к записи
        $count = $result->fetch(PDO::FETCH_ASSOC);

        if ($count) {
            return $count['row_count'];
        }
        return 0;
    }

    /**
     * Возвращает тестирования по направлению
     * @param [] $search - Параметры поиска
     * @return array
     */
    public static function getTestingListByDirection($search)
    {
        $sql = 'SELECT
              testing.id,
              testing.name
            FROM
              testing
              INNER JOIN test ON (testing.test_id = test.id)
              INNER JOIN direction ON (test.direction_id = direction.id)
            WHERE
              direction.id = :direction_id AND
              (direction.flag = 0 OR
              direction.flag = 1) AND
              (test.flag = 0 OR
              test.flag = 1) AND
              (testing.flag = 0 OR
              testing.flag = 1)';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':direction_id', $search['direction_id'], PDO::PARAM_INT);
        $result->execute();
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
     * Начинает тестирование
     * @param [] $testing_begin_info - инфомрация, необходимая для тестирования
     * @return bool
     */
    public static function startTesting($testing_begin_info)
    {
        $testing_state = self::getSessionTestingState();
        if ($testing_state == true)
        {
            header('Location: /main/quiz?qid='.$_SESSION['questions'][0]);
            return;
        }

        if (!is_array($testing_begin_info))
        {
            return false;
        }

        if ($testing_begin_info['testing_result_id'] == null)
        {
            return false;
        }

        $_SESSION['testing_result_id'] = $testing_begin_info['testing_result_id'];
        $_SESSION['questions'] = null; // Вопросы
        $_SESSION['answers'] = null; // Ответы к вопросу
        $_SESSION['question_start_datetime'] = null; // Стартовая метка времени вопроса
        $_SESSION['question_now'] = null; // Текущий вопрос
        $_SESSION['question_time'] = null; // Время ответа на вопрос (в секундах)

        if (!is_array($testing_begin_info['questions']))
        {
            return false;
        }
        $k = 0; // Счетчик
        $filtered_questions = []; // Отфильтрованные вопросы
        if ($testing_begin_info['testing']['is_question_random'] == APP_YES)
        {
            while (true)
            {
                $i = rand(0, count($testing_begin_info['questions'])-1);
                if ($testing_begin_info['questions'][$i]['id'] != null)
                {
                    $filtered_questions[] = $testing_begin_info['questions'][$i]['id'];
                    $testing_begin_info['questions'][$i]['id'] = null;
                    $k++;
                    if ($k == $testing_begin_info['testing']['question_count'])
                    {
                        break;
                    }
                }
            }
        }
        else
        {
            foreach ($testing_begin_info['questions'] as $tbi_item)
            {
                $filtered_questions[] = $tbi_item['id'];
            }
        }
        $counter = 0;
        foreach($filtered_questions as $value)
        {
            $counter++;
            $_SESSION['questions'][] = $value;
            $_SESSION['answers'][$counter] = [$value => null];
            $_SESSION['question_start_datetime'][$value] = null;
            $_SESSION['question_now'] = $value;
            $_SESSION['question_time'][$value] = 0;
        }
        $_SESSION['testing_id'] = $testing_begin_info['testing_result']['testing_id'];
        $_SESSION['time']['start'] = $testing_begin_info['testing_result']['begin_datetime'];
        $_SESSION['testing_started'] = true; // Тестирование началось
        header('Location: /main/quiz?qid='.$_SESSION['questions'][0]);
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
     * Добавляет новое тестирование
     * @param [] $testing - массив с данными
     * @return bool|int
     */
    public static function add($testing)
    {
        $sql = 'INSERT INTO testing (name, test_id, testing_count, question_count, is_question_random,
            is_answer_random, minimum_score, testing_time, testing_time_flag, change_user_id, change_datetime, flag)
          VALUES (:name, :test_id, :testing_count, :question_count, :is_question_random,
            :is_answer_random, :minimum_score, :testing_time, :testing_time_flag, :change_user_id, :change_datetime, :flag)';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':name', $testing['name'], PDO::PARAM_STR);
        $result->bindParam(':test_id', $testing['test_id'], PDO::PARAM_INT);
        $result->bindParam(':testing_count', $testing['testing_count'], PDO::PARAM_INT);
        $result->bindParam(':question_count', $testing['question_count'], PDO::PARAM_INT);
        $result->bindParam(':is_question_random', $testing['is_question_random'], PDO::PARAM_INT);
        $result->bindParam(':is_answer_random', $testing['is_answer_random'], PDO::PARAM_INT);
        $result->bindParam(':minimum_score', $testing['minimum_score'], PDO::PARAM_INT);
        $result->bindParam(':testing_time', $testing['testing_time'], PDO::PARAM_STR);
        $result->bindParam(':testing_time_flag', $testing['testing_time_flag'], PDO::PARAM_INT);
        $result->bindParam(':change_user_id', $testing['change_user_id'], PDO::PARAM_INT);
        $result->bindParam(':change_datetime', $testing['change_datetime'], PDO::PARAM_STR);
        $result->bindParam(':flag', $testing['flag'], PDO::PARAM_INT);
        if($result->execute())
        {
            return $db->lastInsertId();
        }
        return false;
    }

    /**
     * Изменить запись
     * @param [] $testing - массив с данными
     */
    public static function edit($testing)
    {
        $sql = 'UPDATE testing
          SET name = :name, test_id = :test_id, testing_count = :testing_count, question_count = :question_count,
          is_question_random = :is_question_random, is_answer_random = :is_answer_random, minimum_score = :minimum_score,
          testing_time = :testing_time, testing_time_flag = :testing_time_flag, change_user_id = :change_user_id,
          change_datetime = :change_datetime, flag = :flag
          WHERE id = :id AND flag > 0';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $testing['id'], PDO::PARAM_INT);
        $result->bindParam(':name', $testing['name'], PDO::PARAM_STR);
        $result->bindParam(':test_id', $testing['test_id'], PDO::PARAM_INT);
        $result->bindParam(':testing_count', $testing['testing_count'], PDO::PARAM_INT);
        $result->bindParam(':question_count', $testing['question_count'], PDO::PARAM_INT);
        $result->bindParam(':is_question_random', $testing['is_question_random'], PDO::PARAM_INT);
        $result->bindParam(':is_answer_random', $testing['is_answer_random'], PDO::PARAM_INT);
        $result->bindParam(':minimum_score', $testing['minimum_score'], PDO::PARAM_INT);
        $result->bindParam(':testing_time', $testing['testing_time'], PDO::PARAM_STR);
        $result->bindParam(':testing_time_flag', $testing['testing_time_flag'], PDO::PARAM_INT);
        $result->bindParam(':change_user_id', $testing['change_user_id'], PDO::PARAM_INT);
        $result->bindParam(':change_datetime', $testing['change_datetime'], PDO::PARAM_STR);
        $result->bindParam(':flag', $testing['flag'], PDO::PARAM_INT);
        $result->execute();
    }

    /**
     * Удалить тестирование (изменить флаг)
     * @param [] $testing - массив с данными
     */
    public static function delete($testing)
    {
        $sql = 'UPDATE testing
          SET
            change_datetime = :change_datetime, change_user_id = :change_user_id, flag = -1
          WHERE id = :id AND flag > 0';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $testing['id'], PDO::PARAM_INT);
        $result->bindParam(':change_datetime', $testing['change_datetime'], PDO::PARAM_STR);
        $result->bindParam(':change_user_id', $testing['change_user_id'], PDO::PARAM_INT);
        $result->execute();
    }

    /**
     * Возвращает ID тестирования из сессии
     * @return int|bool
     */
    public static function getSessionTesting()
    {
        session_start();
        if (isset($_SESSION['testing_id']))
        {
            return $_SESSION['testing_id'];
        }
        return false;
    }

    /**
     * Удаляет ID тестирования из сессии
     * @return bool
     */
    public static function unsetSessionTesting()
    {
        session_start();
        if (isset($_SESSION['testing_id']))
        {
            unset($_SESSION['testing_id']);
        }
        return false;
    }

    /**
     * Возвращает ID результата тестирования из сессии
     * @return bool|int
     */
    public static function getSessionTestingResultId()
    {
        session_start();
        if (isset($_SESSION['testing_result_id']))
        {
            return $_SESSION['testing_result_id'];
        }
        return false;
    }

    /**
     * Удаляет ID результата тестирования из сессии
     * @return bool
     */
    public static function unsetSessionTestingResultId()
    {
        session_start();
        if (isset($_SESSION['testing_result_id']))
        {
            unset($_SESSION['testing_result_id']);
        }
        return false;
    }

    /**
     * Возвращает состояние тестирования из сессии
     * @return bool
     */
    public static function getSessionTestingState()
    {
        session_start();
        if (isset($_SESSION['testing_started']))
        {
            return $_SESSION['testing_started'];
        }
        return false;
    }

    /**
     * Удаляет состояние тестирования из сессии
     * @return bool
     */
    public static function unsetSessionTestingState()
    {
        session_start();
        if (isset($_SESSION['testing_started']))
        {
            unset($_SESSION['testing_started']);
        }
        return false;
    }

    /**
     * Возвращает время начала тестирования из сессии
     * @return string|bool
     */
    public static function getSessionTestingStartTime()
    {
        session_start();
        if (isset($_SESSION['time']['start']))
        {
            return $_SESSION['time']['start'];
        }
        return false;
    }

    /**
     * Удаляет время начала тестирования из сессии
     * @return bool
     */
    public static function unsetSessionTestingStartTime()
    {
        session_start();
        if (isset($_SESSION['time']['start']))
        {
            unset($_SESSION['time']['start']);
        }
        return false;
    }

    /**
     * Возвращает вопросы из сессии
     * @return array|bool
     */
    public static function getSessionTestingQuestions()
    {
        session_start();
        if (isset($_SESSION['questions']))
        {
            return $_SESSION['questions'];
        }
        return false;
    }

    /**
     * Удаляет вопросы из сессии
     * @return bool
     */
    public static function unsetSessionTestingQuestions()
    {
        session_start();
        if (isset($_SESSION['questions']))
        {
            unset($_SESSION['questions']);
        }
        return false;
    }

    /**
     * Возвращает ответы из сессии
     * @return array|bool
     */
    public static function getSessionTestingAnswers()
    {
        session_start();
        if (isset($_SESSION['answers']))
        {
            return $_SESSION['answers'];
        }
        return false;
    }

    /**
     * Удаляет ответы из сессии
     * @return bool
     */
    public static function unsetSessionTestingAnswers()
    {
        session_start();
        if (isset($_SESSION['answers']))
        {
            unset($_SESSION['answers']);
        }
        return false;
    }

    /**
     * Устанавливает ответ/ответы
     * @param int $question_number - номер вопроса
     * @param int $question_id - ID вопроса
     * @param [] $answers - массив с ответом/ответами
     * @return bool
     */
    public static function setSessionAnswerRespond($question_number, $question_id, $answers)
    {
        session_start();
        if (!isset($_SESSION['answers'][$question_number]))
        {
            return false;
        }

        $_SESSION['answers'][$question_number] = [$question_id => $answers];
        return true;
    }

    /**
     * Устанавливает метку времени начала на вопрос
     * @param int $q_id - ID вопроса
     * @param string $datetime - время
     * @return bool
     */
    public static function setSessionQuestionStartdatetime($q_id, $datetime)
    {
        session_start();
        if ($q_id == null)
        {
            return false;
        }

        if ($datetime == null)
        {
            return false;
        }

        $_SESSION['question_start_datetime'][$q_id] = $datetime;
        return true;
    }

    /**
     * Возвращает метку времени начала на вопрос
     * @param int $q_id - ID вопроса
     * @return bool|string
     */
    public static function getSessionQuestionStartdatetime($q_id)
    {
        session_start();
        if ($q_id == null)
        {
            return false;
        }
        if (isset($_SESSION['question_start_datetime'][$q_id]))
        {
            return $_SESSION['question_start_datetime'][$q_id];
        }
        return false;
    }

    /**
     * Удаляет время начала вопроса из сессии
     * @return bool
     */
    public static function unsetSessionQuestionStartdatetime()
    {
        session_start();
        if (isset($_SESSION['question_start_datetime']))
        {
            unset($_SESSION['question_start_datetime']);
        }
        return false;
    }

    /**
     * Устанавливает текущий вопрос
     * @param int $q_id - ID вопроса
     * @return bool
     */
    public static function setSessionQuestionNow($q_id)
    {
        session_start();
        if ($q_id == null)
        {
            return false;
        }

        if (!isset($_SESSION['question_now']))
        {
            return false;
        }

        $_SESSION['question_now'] = $q_id;
        return true;
    }

    /**
     * Возвращает текущий вопрос
     * @return bool|int
     */
    public static function getSessionQuestionNow()
    {
        session_start();
        if (isset($_SESSION['question_now']))
        {
            return $_SESSION['question_now'];
        }
        return false;
    }

    /**
     * Удаляет текущий вопрос из сессии
     * @return bool
     */
    public static function unsetSessionQuestionNow()
    {
        session_start();
        if (isset($_SESSION['question_now']))
        {
            unset($_SESSION['question_now']);
        }
        return false;
    }

    /**
     * Устанавливает время для вопроса
     * @param int $q_id - ID вопроса
     * @param int $q_time - количество секунд
     * @return bool
     */
    public static  function setSessionQuestionTime($q_id, $q_time)
    {
        session_start();
        if ($q_id == null)
        {
            return false;
        }

        /*if (!isset($_SESSION['question_time'][$q_id]))
        {
            return false;
        }*/

        if ($q_time == null)
        {
            return false;
        }

        $_SESSION['question_time'][$q_id] = $q_time;
        return true;
    }

    /**
     * Возвращает время для вопроса
     * @param int $q_id - ID вопроса
     * @return bool|int
     */
    public static function getSessionQuestionTime($q_id)
    {
        session_start();
        if ($q_id == null)
        {
            return false;
        }
        if (isset($_SESSION['question_time'][$q_id]))
        {
            return $_SESSION['question_time'][$q_id];
        }
        return false;
    }

    /**
     * Удаляет время ответа на вопросы из сессии
     * @return bool
     */
    public static function unsetSessionQuestionTime()
    {
        session_start();
        if (isset($_SESSION['question_time']))
        {
            unset($_SESSION['question_time']);
        }
        return false;
    }
}