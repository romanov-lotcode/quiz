<?php

/**
 * Class Answer
 * Ответы
 */
class Answer
{
    /*****************************************************
     ******************** ПОЛЯ КЛАССА ********************
     *****************************************************/

    /*****************************************************
     ******************* МЕТОДЫ КЛАССА *******************
     *****************************************************/

    /**
     * Получить ответы по параметрам поиска
     * @param [] $search - параметры поиска
     * @return array
     */
    public static function getAnswers($search)
    {
        $sql = 'SELECT
          answer.id,
          answer.name,
          answer.question_id,
          answer.complexity_coefficient,
          answer.change_user_id,
          answer.change_datetime,
          answer.flag
        FROM
          answer
          INNER JOIN question ON (answer.question_id = question.id)
          INNER JOIN test ON (question.test_id = test.id)
          INNER JOIN direction ON (test.direction_id = direction.id)
        WHERE
          answer.question_id = ? AND
          answer.flag >= 0 AND
          question.test_id = ? AND
          question.flag >= 0 AND
          test.direction_id = ? AND
          (test.flag = 0 OR
          test.flag = 1) AND
          (direction.flag = 0 OR
          direction.flag = 1)';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->execute([$search['question_id'], $search['test_id'], $search['direction_id']]);

        // Получение и возврат результатов
        $answers = [];
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $answers[$i] = $row;
            $i++;
        }
        return $answers;
    }

    /**
     * Возвращет количество записей, удовлетворяющих параметрам поиска
     * @param [] $search - параметры поиска
     * @return int
     */
    public static function getTotalAnswers($search)
    {
        $sql = 'SELECT
            COUNT(*) AS row_count
          FROM
          answer
          INNER JOIN question ON (answer.question_id = question.id)
          INNER JOIN test ON (question.test_id = test.id)
          INNER JOIN direction ON (test.direction_id = direction.id)
        WHERE
          answer.question_id = ? AND
          answer.flag >= 0 AND
          question.test_id = ? AND
          question.flag >= 0 AND
          test.direction_id = ? AND
          (test.flag = 0 OR
          test.flag = 1) AND
          (direction.flag = 0 OR
          direction.flag = 1)';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->execute([$search['question_id'], $search['test_id'], $search['direction_id']]);
        // Обращаемся к записи
        $count = $result->fetch(PDO::FETCH_ASSOC);

        if ($count) {
            return $count['row_count'];
        }
        return 0;
    }

    /**
     * Добавить новый ответ
     * @param [] $answer - массив с данными
     * @return bool|int
     */
    public static function add($answer)
    {
        $sql = 'INSERT INTO answer (name, question_id, complexity_coefficient, change_user_id,
          change_datetime, flag)
          VALUES (:name, :question_id, :complexity_coefficient, :change_user_id,
          :change_datetime, :flag)';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':name', $answer['name'], PDO::PARAM_STR);
        $result->bindParam(':question_id', $answer['question_id'], PDO::PARAM_INT);
        $result->bindParam(':complexity_coefficient', $answer['complexity_coefficient'], PDO::PARAM_INT);
        $result->bindParam(':change_user_id', $answer['change_user_id'], PDO::PARAM_INT);
        $result->bindParam(':change_datetime', $answer['change_datetime'], PDO::PARAM_STR);
        $result->bindParam(':flag', $answer['flag'], PDO::PARAM_INT);
        if($result->execute())
        {
            return $db->lastInsertId();
        }
        return false;
    }
}