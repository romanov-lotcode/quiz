<?php

/**
 * Class Testing_Result_Report
 * Отчет результатов тестирования
 */
class Testing_Result_Report
{
    /*****************************************************
     ******************** ПОЛЯ КЛАССА ********************
     *****************************************************/

    /*****************************************************
     ******************* МЕТОДЫ КЛАССА *******************
     *****************************************************/

    /**
     * Возращает отчет результата тестирования по результату тестирования
     * @param int $testing_result_id - ID результата тестирования
     * @return array
     */
    public static function getTestingResultReportByTestingResult($testing_result_id)
    {
        $sql = 'SELECT
              question.name AS question_name,
              question.question_type_id,
              question.explanation AS question_explanation,
              question.path_img AS question_path_img,
              testing_result_report.question_id,
              testing_result_report.answer_id,
              testing_result_report.question_time
            FROM
              testing_result_report
              INNER JOIN question ON (testing_result_report.question_id = question.id)
            WHERE
              testing_result_report.testing_result_id = :testing_result_id';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':testing_result_id', $testing_result_id, PDO::PARAM_INT);
        $result->execute();
        // Получение и возврат результатов
        $testing_result_report_list = [];
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $testing_result_report_list[$i] = $row;
            $i++;
        }
        return $testing_result_report_list;
    }


    /**
     * Добавляет новую запись
     * @param [] $testing_result_report - Массив с данными
     * @return bool|string
     */
    public static function add($testing_result_report)
    {
        $sql = 'INSERT INTO testing_result_report (testing_result_id, question_id, answer_id, question_time)
          VALUES (:testing_result_id, :question_id, :answer_id, :question_time)';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':testing_result_id', $testing_result_report['testing_result_id'], PDO::PARAM_INT);
        $result->bindParam(':question_id', $testing_result_report['question_id'], PDO::PARAM_INT);
        $result->bindParam(':answer_id', $testing_result_report['answer_id'], PDO::PARAM_INT);
        $result->bindParam(':question_time', $testing_result_report['question_time'], PDO::PARAM_INT);
        if($result->execute())
        {
            return $db->lastInsertId();
        }
        return false;
    }

    /**
     * Удаляет отчет результата тестирования
     * @param int $testing_result_id - ID результата тестирования
     * @return bool
     */
    public static function delete($testing_result_id)
    {
        $sql = 'DELETE FROM testing_result_report WHERE testing_result_id = :testing_result_id';
        $db = Database::getConnection();
        $result = $db->prepare($sql);
        $result->bindParam(':testing_result_id', $testing_result_id, PDO::PARAM_INT);
        if ($result->execute())
        {
            return true;
        }
        return false;
    }
}