<?php

/**
 * Class Question
 * Вопросы
 */
class Question
{
    /*****************************************************
     ******************** ПОЛЯ КЛАССА ********************
     *****************************************************/

    /*****************************************************
     ******************* МЕТОДЫ КЛАССА *******************
     *****************************************************/

    /**
     * Получить вопросы по параметрам поиска
     * @param [] $search - параметры поиска
     * @return array
     */
    public static function getQuestions($search)
    {
        $sql = 'SELECT
            question.id,
            question.name,
            question.number,
            question.explanation,
            question.`comment`,
            question.test_id,
            question.path_img,
            question.question_type_id,
            question.question_time,
            question.question_time_flag,
            question.change_user_id,
            question.change_datetime,
            question.flag
          FROM
            question
            INNER JOIN test ON (question.test_id = test.id)
            INNER JOIN direction ON (test.direction_id = direction.id)
          WHERE
            question.test_id = ? AND
            question.flag >= 0 AND
            test.direction_id = ? AND
            (test.flag = 0 OR
            test.flag = 1) AND
            (direction.flag = 0 OR
            direction.flag = 1) AND
            question.name LIKE ?
          ORDER BY
            question.number';

        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $search['name'] = '%' . $search['name'] . '%';

        $result->execute([$search['test_id'], $search['direction_id'], $search['name']]);

        // Получение и возврат результатов
        $questions = [];
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $questions[$i] = $row;
            $i++;
        }
        return $questions;
    }

    /**
     * Возвращет количество записей, удовлетворяющих параметрам поиска
     * @param [] $search - параметры поиска
     * @return int
     */
    public static function getTotalQuestions($search)
    {
        $search['name'] = '%' . $search['name'] . '%';
        $sql = 'SELECT
            COUNT(*) AS row_count
          FROM
            question
            INNER JOIN test ON (question.test_id = test.id)
            INNER JOIN direction ON (test.direction_id = direction.id)
          WHERE
            question.test_id = ? AND
            question.flag >= 0 AND
            test.direction_id = ? AND
            (test.flag = 0 OR
            test.flag = 1) AND
            (direction.flag = 0 OR
            direction.flag = 1) AND
            question.name LIKE ?';

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
     * Получить типы вопросов
     * @return array
     */
    public static function getQuestionTypes()
    {
        $sql = 'SELECT * FROM question_type WHERE question_type.flag = 0 OR question_type.flag = 1';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->execute();
        // Получение и возврат результатов
        $question_types = [];
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $question_types[$i] = $row;
            $i++;
        }
        return $question_types;
    }

    /**
     * Добавляет новый вопрос
     * @param [] $question - массив с данными
     * @return bool|int
     */
    public static function add($question)
    {
        $sql = 'INSERT INTO question (name, number, question_type_id, explanation, comment, test_id, path_img,
          question_time, question_time_flag, change_user_id, change_datetime, flag)
          VALUES (:name, :number, :question_type_id, :explanation, :comment, :test_id, :path_img,
          :question_time, :question_time_flag, :change_user_id, :change_datetime, :flag)';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':name', $question['name'], PDO::PARAM_STR);
        $result->bindParam(':number', $question['number'], PDO::PARAM_INT);
        $result->bindParam(':question_type_id', $question['question_type_id'], PDO::PARAM_INT);
        $result->bindParam(':explanation', $question['explanation'], PDO::PARAM_STR);
        $result->bindParam(':comment', $question['comment'], PDO::PARAM_STR);
        $result->bindParam(':test_id', $question['test_id'], PDO::PARAM_INT);
        $result->bindParam(':path_img', $question['path_img'], PDO::PARAM_STR);
        $result->bindParam(':question_time', $question['question_time'], PDO::PARAM_STR);
        $result->bindParam(':question_time_flag', $question['question_time_flag'], PDO::PARAM_INT);
        $result->bindParam(':change_user_id', $question['change_user_id'], PDO::PARAM_INT);
        $result->bindParam(':change_datetime', $question['change_datetime'], PDO::PARAM_STR);
        $result->bindParam(':flag', $question['flag'], PDO::PARAM_INT);
        if($result->execute())
        {
            return $db->lastInsertId();
        }
        return false;
    }

    /**
     * Обновить путь картинки
     * @param int $id - ID картинки
     * @param string $path - путь
     */
    public static function updatePathImg($id, $path)
    {
        $sql =  $sql = 'UPDATE question
          SET path_img = :path_img
          WHERE id = :id AND flag > 0';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':path_img', $path, PDO::PARAM_INT);
        $result->execute();
    }
}