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
}