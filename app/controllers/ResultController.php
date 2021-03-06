<?php

class ResultController extends BaseController
{
    public function actionIndex()
    {
        $user_right = parent::getUserRight();
        $u_id = User::checkLogged();
        $search = [];
        $date_converter = new Date_Converter();

        $search['type'] = 1; // Тип поиска.
        // Если 1, то будут показаны завершенные тестирования
        // Если 2, то будут показаны все тестирования
        $page = 1;
        $errors = false;

        $back_link_default = '/main/index';

        $back_link = '';
        $back_link_param = '';

        $url_link = '';
        $url_param = '';

        $user_id_to_view = 0;

        $is_can = false;
        $is_can_moderator_result = false;
        $is_can_other_result_view = false;

        $index_number = 1;
        $testing_results = [];
        $total = 0;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_OTHER_RESULT_VIEW)
            {
                $is_can_other_result_view = true;
            }
            if ($u_r['right_name'] == CAN_MODERATOR_RESULT)
            {
                $is_can_moderator_result = true;
            }
            if ($u_r['right_name'] == CAN_RESULT_VIEW)
            {
                $is_can = true;
            }
            if ($is_can === true && $is_can_other_result_view === true && $is_can_moderator_result === true)
            {
                break;
            }
        }

        if (isset($_GET['pf']))
        {
            $search['pf'] = htmlspecialchars($_GET['pf']);
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

        if (isset($_GET['type']))
        {
            $search['type'] = htmlspecialchars($_GET['type']);
        }

        // Если пользователь пришел со страницы
        if ($search['pf'] == 'uti')
        {
            $back_link = '/user_testing/index?';
            if (isset($_GET['s_direction']))
            {
                $search['s_direction'] = htmlspecialchars($_GET['s_direction']);
            }
            $back_link_param .= '&s_direction='.$search['s_direction'];
            if (isset($_GET['s_testing']))
            {
                $search['s_testing'] = htmlspecialchars($_GET['s_testing']);
            }
            $back_link_param .= '&s_testing='.$search['s_testing'];
            if (isset($_GET['s_user_group']))
            {
                $search['s_user_group'] = htmlspecialchars($_GET['s_user_group']);
            }
            $back_link_param .= '&s_user_group='.$search['s_user_group'];
            if (isset($_GET['s_name']))
            {
                $search['s_name'] = htmlspecialchars($_GET['s_name']);
            }
            $back_link_param .= '&s_name='.$search['s_name'];
        }
        if ($search['pf'] == 'ui')
        {
            $back_link = '/user/index?';
            if (isset($_GET['s_name']))
            {
                $search['s_name'] = htmlspecialchars($_GET['s_name']);
            }
            $back_link_param .= '&s_name='.$search['s_name'];
            if (isset($_GET['p_page']))
            {
                $search['p_page'] = htmlspecialchars($_GET['p_page']);
            }
            $back_link_param .= '&p_page='.$search['p_page'];
        }
        if (isset($_GET['uid']))
        {
            $search['user_id'] = htmlspecialchars($_GET['uid']);
        }

        if ($is_can_other_result_view && $search['user_id'] > 0)
        {
            $user_id_to_view = $search['user_id'];
        }
        else
        {
            $user_id_to_view = $u_id;
        }
        $search['user_id'] = $user_id_to_view;

        if ($back_link != null)
        {
            $url_link = $back_link.$back_link_param;
            $url_param .= $back_link_param.'&pf='.$search['pf'];
        }
        else
        {
            $url_link = $back_link_default;
        }
        $url_param .= '&page='. $page.'&user_id='.$search['user_id'];

        if ($is_can)
        {
            $testing_results = Testing_Result::getTestingResults($search, $page);
            $total = Testing_Result::getTotalTestingResults($search);
            $index_number = Testing_Result::getIndexNumber($page);
            $pagination = new Pagination($total, $page, Testing_Result::SHOW_BY_DEFAULT, 'page=');

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

        $back_link_param = '';

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

        $points_scored = 0; // Набранные баллы
        $points_max = 0; // Максимальное количество баллов
        $points_min = 0; // Коэфициент прохождения

        $total_question_time = 0; // Общее время вопросов

        $is_result_actual = true; // Актуальны ли результаты
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

        if (isset($_GET['pf']))
        {
            $search['pf'] = htmlspecialchars($_GET['pf']);
        }

        if (isset($_GET['type']))
        {
            $search['type'] = htmlspecialchars($_GET['type']);
        }

        // Если пользователь пришел со страницы
        if ($search['pf'] == 'uti')
        {
            if (isset($_GET['s_direction']))
            {
                $search['s_direction'] = htmlspecialchars($_GET['s_direction']);
            }
            $back_link_param .= '&s_direction='.$search['s_direction'];
            if (isset($_GET['s_testing']))
            {
                $search['s_testing'] = htmlspecialchars($_GET['s_testing']);
            }
            $back_link_param .= '&s_testing='.$search['s_testing'];
            if (isset($_GET['s_user_group']))
            {
                $search['s_user_group'] = htmlspecialchars($_GET['s_user_group']);
            }
            $back_link_param .= '&s_user_group='.$search['s_user_group'];
            if (isset($_GET['s_name']))
            {
                $search['s_name'] = htmlspecialchars($_GET['s_name']);
            }
            $back_link_param .= '&s_name='.$search['s_name'];
        }
        if ($search['pf'] == 'ui')
        {
            if (isset($_GET['s_name']))
            {
                $search['s_name'] = htmlspecialchars($_GET['s_name']);
            }
            $back_link_param .= '&s_name='.$search['s_name'];
            if (isset($_GET['p_page']))
            {
                $search['p_page'] = htmlspecialchars($_GET['p_page']);
            }
            $back_link_param .= '&p_page='.$search['p_page'];
        }

        if ($search['pf'] != null)
        {
            $url_param .= $back_link_param.'&pf='.$search['pf'];
        }

        if ($is_can_other_result_view && $search['user_id'] > 0)
        {
            $user_id_to_view = $search['user_id'];
        }
        else
        {
            $user_id_to_view = $u_id;
        }
        $url_param .= '&page='. $page.'&user_id='.$user_id_to_view;

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

        $points_min = $testing_result_info['minimum_score'];

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
                $filtered_result_report[$trr_value['question_id']]['question_change_flag'] = 0;
                $filtered_result_report[$trr_value['question_id']]['question_type_id'] = $trr_value['question_type_id'];
                $filtered_result_report[$trr_value['question_id']]['question_explanation'] = $trr_value['question_explanation'];
                $filtered_result_report[$trr_value['question_id']]['question_path_img'] = $trr_value['question_path_img'];
                $filtered_result_report[$trr_value['question_id']]['question_time'] = $trr_value['question_time'];
                $total_question_time += $trr_value['question_time'];
                $filtered_result_report[$trr_value['question_id']]['answers'][] = $trr_value['answer_id'];
                $filtered_result_report[$trr_value['question_id']]['all_answers'] = Answer::getAnswers($trr_value['question_id'], 1);
                $filtered_result_report[$trr_value['question_id']]['view_answers'] = [];

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
                    $filtered_result_report[$trr_value['question_id']]['question_change_flag'] = 0;
                    $filtered_result_report[$trr_value['question_id']]['question_type_id'] = $trr_value['question_type_id'];
                    $filtered_result_report[$trr_value['question_id']]['question_explanation'] = $trr_value['question_explanation'];
                    $filtered_result_report[$trr_value['question_id']]['question_path_img'] = $trr_value['question_path_img'];
                    $filtered_result_report[$trr_value['question_id']]['question_time'] = $trr_value['question_time'];
                    $total_question_time += $trr_value['question_time'];
                    $filtered_result_report[$trr_value['question_id']]['answers'][] = $trr_value['answer_id'];
                    $filtered_result_report[$trr_value['question_id']]['all_answers'] = Answer::getAnswers($trr_value['question_id'], 1);
                    $filtered_result_report[$trr_value['question_id']]['view_answers'] = [];
                }
            }
            $question_id_temp = $trr_value['question_id'];
        }

        $temp = []; // Временные данные
        foreach ($filtered_result_report as $frr_question_id => $frr_value)
        {
            $temp[$frr_question_id]['question_name'] = $frr_value['question_name'];
            $temp[$frr_question_id]['question_type_id'] = $frr_value['question_type_id'];
            $temp[$frr_question_id]['question_explanation'] = $frr_value['question_explanation'];
            $temp[$frr_question_id]['question_path_img'] = $frr_value['question_path_img'];
            $temp[$frr_question_id]['question_time'] = $frr_value['question_time'];
            $temp[$frr_question_id]['question_change_flag'] = $frr_value['question_change_flag'];

            // Правильно ли отвечен вопрос

            // Изменился ли вопрос после ответа
            if (count($frr_value['answers']) > 1 && $frr_value['question_type_id'] == 0)
            {
                // Если количество ответов более одного, а тип вопроса "один к одному" - значит вопрос был изменен
                $temp[$frr_question_id]['question_change_flag'] = 1;
                $is_result_actual = false; // Резульатты уже не актуальны
            }

            // Заносим правильные ответы в отдельный массив
            foreach ($frr_value['all_answers'] as $frrv_aa_key => $frrv_aa_value)
            {
                if ($frrv_aa_value['flag'] == FLAG_ON || $frrv_aa_value['flag'] == FLAG_NO_CHANGE)
                {
                    if ($frrv_aa_value['complexity_coefficient'] > 0)
                    {
                        $points_max += $frrv_aa_value['complexity_coefficient'];
                        $temp[$frr_question_id]['view_answers']['right'][] = $frrv_aa_value;
                    }
                }
            }

            foreach ($frr_value['answers'] as $frrv_a_key => $frrv_a_answer_id)
            {
                foreach ($frr_value['all_answers'] as $frrv_aa_key => $frrv_aa_value)
                {
                    if ($frrv_a_answer_id == $frrv_aa_value['id'])
                    {
                        $points_scored += $frrv_aa_value['complexity_coefficient'];
                        $temp[$frr_question_id]['view_answers']['answered'][$frrv_a_answer_id] = $frrv_aa_value;
                    }
                }
            }
        }

        if ($points_scored >= $points_min)
        {
            $is_testing_complete = true;
        }
        $filtered_result_report = $temp;
        $temp = '';
        $total_question_time = $date_converter->secondsToTime($total_question_time);
        if ($total_question_time['hours'] > 0)
        {
            $temp .= $total_question_time['hours'] . ' ч ';
        }
        if ($total_question_time['minutes'] > 0)
        {
            $temp .= $total_question_time['minutes'] . ' мин ';
        }
        $temp .= $total_question_time['seconds'] .' сек';
        $total_question_time = $temp;
        $temp = null;

        $temp_counter_right_answers = 0; // Временное количество правильных ответов
        $temp_counter_wrong_answers = 0; // Временное количество неправильныъ ответов
        $wrong_answers = [];
        $scip_questions = [];
        foreach ($filtered_result_report as $frr_question_id => $frr_value)
        {
            $is_scip = false;
            $is_wrong = false;
            $is_right = false;
            $i_asnwered = 0;
            $temp_counter_right_answers = count($frr_value['view_answers']['right']);
            if ($temp_counter_right_answers != count($frr_value['view_answers']['answered']))
            {
                if (!is_array($frr_value['view_answers']['answered']) || $frr_value['view_answers']['answered'] == null)
                {
                    $is_scip = true;
                    $scip_questions[] = $frr_question_id;
                    goto _gt_continue;
                }
                $is_wrong = true;
                foreach ($frr_value['view_answers']['answered'] as $frr_vaa_answer_id => $frr_vaa_answer_value)
                {
                    if (!in_array($frr_vaa_answer_value, $frr_value['view_answers']['right']))
                    {
                        $wrong_answers[$frr_question_id][] = $frr_vaa_answer_id;
                        $is_wrong = true;
                    }
                }
            }
            else
            {
                if (!is_array($frr_value['view_answers']['answered']) || $frr_value['view_answers']['answered'] == null)
                {
                    $is_scip = true;
                    $scip_questions[] = $frr_question_id;
                    goto _gt_continue;
                }
                foreach ($frr_value['view_answers']['answered'] as $frr_vaa_answer_id => $frr_vaa_answer_value)
                {
                    if (in_array($frr_vaa_answer_value, $frr_value['view_answers']['right']))
                    {
                        $i_asnwered++;
                        if ($temp_counter_right_answers == $i_asnwered)
                        {
                            $is_right = true;
                        }

                    }
                    else
                    {
                        $wrong_answers[$frr_question_id][] = $frr_vaa_answer_id;
                        $is_wrong = true;
                    }
                }
            }

            _gt_continue:
            if ($is_wrong == true)
            {
                $count_wrong++;
            }
            if ($is_right == true)
            {
                $count_correct++;
            }
            if ($is_scip == true)
            {
                $count_scip++;
            }
        }

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

    public function actionPrint()
    {
        $user_right = parent::getUserRight();
        $u_id = User::checkLogged();
        $search = [];
        $errors = false;

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

        $points_scored = 0; // Набранные баллы
        $points_max = 0; // Максимальное количество баллов
        $points_min = 0; // Коэфициент прохождения

        $total_question_time = 0; // Общее время вопросов

        $is_result_actual = true; // Актуальны ли результаты
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

        $points_min = $testing_result_info['minimum_score'];

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
                $filtered_result_report[$trr_value['question_id']]['question_change_flag'] = 0;
                $filtered_result_report[$trr_value['question_id']]['question_type_id'] = $trr_value['question_type_id'];
                $filtered_result_report[$trr_value['question_id']]['question_explanation'] = $trr_value['question_explanation'];
                $filtered_result_report[$trr_value['question_id']]['question_path_img'] = $trr_value['question_path_img'];
                $filtered_result_report[$trr_value['question_id']]['question_time'] = $trr_value['question_time'];
                $total_question_time += $trr_value['question_time'];
                $filtered_result_report[$trr_value['question_id']]['answers'][] = $trr_value['answer_id'];
                $filtered_result_report[$trr_value['question_id']]['all_answers'] = Answer::getAnswers($trr_value['question_id'], 1);
                $filtered_result_report[$trr_value['question_id']]['view_answers'] = [];

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
                    $filtered_result_report[$trr_value['question_id']]['question_change_flag'] = 0;
                    $filtered_result_report[$trr_value['question_id']]['question_type_id'] = $trr_value['question_type_id'];
                    $filtered_result_report[$trr_value['question_id']]['question_explanation'] = $trr_value['question_explanation'];
                    $filtered_result_report[$trr_value['question_id']]['question_path_img'] = $trr_value['question_path_img'];
                    $filtered_result_report[$trr_value['question_id']]['question_time'] = $trr_value['question_time'];
                    $total_question_time += $trr_value['question_time'];
                    $filtered_result_report[$trr_value['question_id']]['answers'][] = $trr_value['answer_id'];
                    $filtered_result_report[$trr_value['question_id']]['all_answers'] = Answer::getAnswers($trr_value['question_id'], 1);
                    $filtered_result_report[$trr_value['question_id']]['view_answers'] = [];
                }
            }
            $question_id_temp = $trr_value['question_id'];
        }

        $temp = []; // Временные данные
        foreach ($filtered_result_report as $frr_question_id => $frr_value)
        {
            $temp[$frr_question_id]['question_name'] = $frr_value['question_name'];
            $temp[$frr_question_id]['question_type_id'] = $frr_value['question_type_id'];
            $temp[$frr_question_id]['question_explanation'] = $frr_value['question_explanation'];
            $temp[$frr_question_id]['question_path_img'] = $frr_value['question_path_img'];
            $temp[$frr_question_id]['question_time'] = $frr_value['question_time'];
            $temp[$frr_question_id]['question_change_flag'] = $frr_value['question_change_flag'];

            // Правильно ли отвечен вопрос

            // Изменился ли вопрос после ответа
            if (count($frr_value['answers']) > 1 && $frr_value['question_type_id'] == 0)
            {
                // Если количество ответов более одного, а тип вопроса "один к одному" - значит вопрос был изменен
                $temp[$frr_question_id]['question_change_flag'] = 1;
                $is_result_actual = false; // Резульатты уже не актуальны
            }

            // Заносим правильные ответы в отдельный массив
            foreach ($frr_value['all_answers'] as $frrv_aa_key => $frrv_aa_value)
            {
                if ($frrv_aa_value['flag'] == FLAG_ON || $frrv_aa_value['flag'] == FLAG_NO_CHANGE)
                {
                    if ($frrv_aa_value['complexity_coefficient'] > 0)
                    {
                        $points_max += $frrv_aa_value['complexity_coefficient'];
                        $temp[$frr_question_id]['view_answers']['right'][] = $frrv_aa_value;
                    }
                }
            }

            foreach ($frr_value['answers'] as $frrv_a_key => $frrv_a_answer_id)
            {
                foreach ($frr_value['all_answers'] as $frrv_aa_key => $frrv_aa_value)
                {
                    if ($frrv_a_answer_id == $frrv_aa_value['id'])
                    {
                        $points_scored += $frrv_aa_value['complexity_coefficient'];
                        $temp[$frr_question_id]['view_answers']['answered'][$frrv_a_answer_id] = $frrv_aa_value;
                    }
                }
            }
        }

        if ($points_scored >= $points_min)
        {
            $is_testing_complete = true;
        }
        $filtered_result_report = $temp;
        $temp = '';
        $total_question_time = $date_converter->secondsToTime($total_question_time);
        if ($total_question_time['hours'] > 0)
        {
            $temp .= $total_question_time['hours'] . ' ч ';
        }
        if ($total_question_time['minutes'] > 0)
        {
            $temp .= $total_question_time['minutes'] . ' мин ';
        }
        $temp .= $total_question_time['seconds'] .' сек';
        $total_question_time = $temp;
        $temp = null;

        $temp_counter_right_answers = 0; // Временное количество правильных ответов
        $temp_counter_wrong_answers = 0; // Временное количество неправильныъ ответов
        $wrong_answers = [];
        $scip_questions = [];
        foreach ($filtered_result_report as $frr_question_id => $frr_value)
        {
            $is_scip = false;
            $is_wrong = false;
            $is_right = false;
            $i_asnwered = 0;
            $temp_counter_right_answers = count($frr_value['view_answers']['right']);
            if ($temp_counter_right_answers != count($frr_value['view_answers']['answered']))
            {
                if (!is_array($frr_value['view_answers']['answered']) || $frr_value['view_answers']['answered'] == null)
                {
                    $is_scip = true;
                    $scip_questions[] = $frr_question_id;
                    goto _gt_continue;
                }
                $is_wrong = true;
                foreach ($frr_value['view_answers']['answered'] as $frr_vaa_answer_id => $frr_vaa_answer_value)
                {
                    if (!in_array($frr_vaa_answer_value, $frr_value['view_answers']['right']))
                    {
                        $wrong_answers[$frr_question_id][] = $frr_vaa_answer_id;
                        $is_wrong = true;
                    }
                }
            }
            else
            {
                if (!is_array($frr_value['view_answers']['answered']) || $frr_value['view_answers']['answered'] == null)
                {
                    $is_scip = true;
                    $scip_questions[] = $frr_question_id;
                    goto _gt_continue;
                }
                foreach ($frr_value['view_answers']['answered'] as $frr_vaa_answer_id => $frr_vaa_answer_value)
                {
                    if (in_array($frr_vaa_answer_value, $frr_value['view_answers']['right']))
                    {
                        $i_asnwered++;
                        if ($temp_counter_right_answers == $i_asnwered)
                        {
                            $is_right = true;
                        }

                    }
                    else
                    {
                        $wrong_answers[$frr_question_id][] = $frr_vaa_answer_id;
                        $is_wrong = true;
                    }
                }
            }

            _gt_continue:
            if ($is_wrong == true)
            {
                $count_wrong++;
            }
            if ($is_right == true)
            {
                $count_correct++;
            }
            if ($is_scip == true)
            {
                $count_scip++;
            }
        }

        _gt_view:
        if ($is_can)
        {
            include_once APP_VIEWS.'result/print.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }

    public function actionDelete()
    {
        $user_right = parent::getUserRight();
        $u_id = User::checkLogged();
        $search = [];
        $page = 1;
        $errors = false;

        $page = 1;
        $errors = false;

        $back_link_param = '';

        $url_param = '';

        $user_id_to_view = 0;

        $is_can = false;

        $index_number = 1;
        $results = [];
        $total = 0;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_RESULT)
            {
                $is_can = true;
            }
            if ($is_can === true)
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

        if (isset($_GET['pf']))
        {
            $search['pf'] = htmlspecialchars($_GET['pf']);
        }

        if (isset($_GET['type']))
        {
            $search['type'] = htmlspecialchars($_GET['type']);
        }

        // Если пользователь пришел со страницы
        if ($search['pf'] == 'uti')
        {
            if (isset($_GET['s_direction']))
            {
                $search['s_direction'] = htmlspecialchars($_GET['s_direction']);
            }
            $back_link_param .= '&s_direction='.$search['s_direction'];
            if (isset($_GET['s_testing']))
            {
                $search['s_testing'] = htmlspecialchars($_GET['s_testing']);
            }
            $back_link_param .= '&s_testing='.$search['s_testing'];
            if (isset($_GET['s_user_group']))
            {
                $search['s_user_group'] = htmlspecialchars($_GET['s_user_group']);
            }
            $back_link_param .= '&s_user_group='.$search['s_user_group'];
            if (isset($_GET['s_name']))
            {
                $search['s_name'] = htmlspecialchars($_GET['s_name']);
            }
            $back_link_param .= '&s_name='.$search['s_name'];
        }
        if ($search['pf'] == 'ui')
        {
            if (isset($_GET['s_name']))
            {
                $search['s_name'] = htmlspecialchars($_GET['s_name']);
            }
            $back_link_param .= '&s_name='.$search['s_name'];
            if (isset($_GET['p_page']))
            {
                $search['p_page'] = htmlspecialchars($_GET['p_page']);
            }
            $back_link_param .= '&p_page='.$search['p_page'];
        }

        if ($search['pf'] != null)
        {
            $url_param .= $back_link_param.'&pf='.$search['pf'];
        }


        if ($is_can && $search['user_id'] > 0)
        {
            $user_id_to_view = $search['user_id'];
        }
        else
        {
            $user_id_to_view = $u_id;
        }

        $testing_result_info = Testing_Result::getTestingResult($search['testing_result_id'], $user_id_to_view);



        if (isset($_POST['yes']))
        {
            if($is_can)
            {
                Testing_Result::delete($search['testing_result_id']);
                Testing_Result_Report::delete($search['testing_result_id']);
                $total = Testing_Result::getTotalTestingResults($search);
                if ($total <= Testing_Result::SHOW_BY_DEFAULT)
                {
                    $page = 1;
                }
                $url_param .= '&page='.$page.'&user_id='.$user_id_to_view;
                header('Location: /result/index?'.$url_param);
            }
        }
        $url_param .= '&page='.$page.'&user_id='.$user_id_to_view;
        if (isset($_POST['no']))
        {
            header('Location: /result/index?'.$url_param);
        }


        if ($is_can)
        {
            include_once APP_VIEWS.'result/delete.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }
}