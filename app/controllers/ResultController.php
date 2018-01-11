<?php

class ResultController extends BaseController
{
    public function actionIndex()
    {
        $user_right = parent::getUserRight();
        $u_id = User::checkLogged();
        $search = [];
        $page = 1;
        $errors = false;

        $user_id_to_view = 0;

        $is_can = false;
        $is_can_other_result_view = false;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_OTHER_RESULT_VIEW)
            {
                $is_can_other_result_view = true;
            }
            if ($u_r['right_name'] == CAN_RESULT_VIEW)
            {
                $is_can = true;
            }
            if ($is_can === true && $is_can_other_result_view === true)
            {
                break;
            }
        }

        if (isset($_GET['testing_result_id']))
        {
            $search['testing_result_id'] = intval(htmlspecialchars($_GET['testing_result_id']));
        }

        if (isset($_GET['user_id']))
        {
            $search['user_id'] = intval(htmlspecialchars($_GET['user_id']));
        }

        if (isset($_GET['page']))
        {
            $page = intval(htmlspecialchars($_GET['page']));
            if ($page < 1)
            {
                $page = 1;
            }
        }



        if ($is_can)
        {
            include_once APP_VIEWS.'result/index.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }

    public function actionView()
    {
        $user_right = parent::getUserRight();
        $u_id = User::checkLogged();
        $search = [];
        $page = 1;
        $errors = false;

        $url_link = '/result/index?';
        $url_param = '';

        $testing_result_info = []; // Массив с данными отчета результата
        $testing_result_report = []; // Массив с вопросами и ответами результата
        $filtered_result_report = []; // Отфильтрованный массив с данными

        $user_id_to_view = 0;

        $is_can = false;
        $is_can_other_result_view = false;
        $is_can_view_correct_answer = false; // Показывать/Не показывать ответы для тестирования

        $count_wrong = 0;
        $count_scip = 0;
        $count_correct = 0;

        $is_testing_complete = false;

        $date_converter = new Date_Converter();
        $end_testing_date = null; // Дата завершения тестирования

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_OTHER_RESULT_VIEW)
            {
                $is_can_other_result_view = true;
            }
            if ($u_r['right_name'] == CAN_RESULT_VIEW)
            {
                $is_can = true;
            }
            if ($u_r['right_name'] == CAN_VIEW_CORRECT_ANSWER)
            {
                $is_can_view_correct_answer = true;
            }
            if ($is_can === true && $is_can_other_result_view === true && $is_can_view_correct_answer === true)
            {
                break;
            }
        }

        if (isset($_GET['testing_result_id']))
        {
            $search['testing_result_id'] = intval(htmlspecialchars($_GET['testing_result_id']));
        }

        if (isset($_GET['user_id']))
        {
            $search['user_id'] = intval(htmlspecialchars($_GET['user_id']));
        }

        if (isset($_GET['page']))
        {
            $page = intval(htmlspecialchars($_GET['page']));
            if ($page < 1)
            {
                $page = 1;
            }
        }


        if ($is_can_other_result_view && $search['user_id'] > 0)
        {
            $user_id_to_view = $search['user_id'];
        }
        else
        {
            $user_id_to_view = $u_id;
        }

        $testing_result_info = Testing_Result::getTestingResult($search['testing_result_id'], $user_id_to_view);

        if ($testing_result_info['is_result_view'] == APP_YES)
        {
            $is_can_view_correct_answer = true;
        }

        if (!is_array($testing_result_info) && count($testing_result_info) < 1)
        {
            $errors['no_testing_result'] = 'Результат не найден';
            goto _gt_view;
        }

        $temp = null;
        $temp = $testing_result_info['end_datetime'];
        $temp = $date_converter->datetimeToDateOrTime($temp, 1);
        $end_testing_date = $date_converter->dateSplit($temp, 1);
        $temp = null;
        $temp = $date_converter->getMonthName($end_testing_date['month'], 3);
        $end_testing_date = trim($end_testing_date['day'] . ' ' . $temp . ' ' . $end_testing_date['year']);

        $testing_result_report = Testing_Result_Report::getTestingResultReportByTestingResult($search['testing_result_id']);

        if (!is_array($testing_result_report) && count($testing_result_report) < 1)
        {
            $count_scip = $testing_result_info['question_count'];
            goto _gt_view;
        }

        $question_id_temp = 0;
        foreach ($testing_result_report as $trr_key => $trr_value)
        {
            if ($question_id_temp == 0)
            {
                $filtered_result_report[$trr_value['question_id']]['question_name'] = $trr_value['question_name'];
                $filtered_result_report[$trr_value['question_id']]['question_type_id'] = $trr_value['question_type_id'];
                $filtered_result_report[$trr_value['question_id']]['question_explanation'] = $trr_value['question_explanation'];
                $filtered_result_report[$trr_value['question_id']]['question_path_img'] = $trr_value['question_path_img'];
                $filtered_result_report[$trr_value['question_id']]['question_time'] = $trr_value['question_time'];
                $filtered_result_report[$trr_value['question_id']]['answers'][] = $trr_value['answer_id'];
                $filtered_result_report[$trr_value['question_id']]['all_answers'] = Answer::getAnswers($trr_value['question_id'], 1);
            }
            else
            {
                if ($trr_value['question_id'] == $question_id_temp)
                {
                    $filtered_result_report[$trr_value['question_id']]['answers'][] = $trr_value['answer_id'];
                }
                else
                {
                    $filtered_result_report[$trr_value['question_id']]['question_name'] = $trr_value['question_name'];
                    $filtered_result_report[$trr_value['question_id']]['question_type_id'] = $trr_value['question_type_id'];
                    $filtered_result_report[$trr_value['question_id']]['question_explanation'] = $trr_value['question_explanation'];
                    $filtered_result_report[$trr_value['question_id']]['question_path_img'] = $trr_value['question_path_img'];
                    $filtered_result_report[$trr_value['question_id']]['question_time'] = $trr_value['question_time'];
                    $filtered_result_report[$trr_value['question_id']]['answers'][] = $trr_value['answer_id'];
                    $filtered_result_report[$trr_value['question_id']]['all_answers'] = Answer::getAnswers($trr_value['question_id'], 1);
                }
            }
            $question_id_temp = $trr_value['question_id'];
        }
        print_r($filtered_result_report);






        _gt_view:
        if ($is_can)
        {
            include_once APP_VIEWS.'result/view.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }
}