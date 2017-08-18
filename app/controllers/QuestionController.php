<?php

class QuestionController extends BaseController
{
    public function actionIndex()
    {
        $user_right = parent::getUserRight();
        $app_state = new App_State();
        $url_param = '';
        $is_can = false;
        $is_can_test = false;
        $is_can_answer = false;
        $option_direction_selected = null;
        $search = [];
        $page = 1;
        $index_number = 0;
        $questions = [];
        $total = 0;
        $test = null;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_QUESTION)
            {
                $is_can = true;
            }
            if ($u_r['right_name'] == CAN_MODERATOR_TEST)
            {
                $is_can_test = true;
            }
            if ($u_r['right_name'] == CAN_MODERATOR_ANSWER)
            {
                $is_can_answer = true;
            }
            if ($is_can === true && $is_can_test === true && $is_can_answer === true)
            {
                break;
            }
        }

        if (isset($_GET['s_direction']))
        {
            $search['direction_id'] = htmlspecialchars($_GET['s_direction']);
        }
        if (isset($_GET['tid']))
        {
            $search['test_id'] = htmlspecialchars($_GET['tid']);
        }
        if (isset($_GET['s_name']))
        {
            $search['test_name'] = htmlspecialchars($_GET['s_name']);
        }
        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }

        if ($page < 1)
        {
            $page = 1;
        }

        if (isset($_GET['s_q_name']))
        {
            $search['name'] = htmlspecialchars($_GET['s_q_name']);
        }

        $test = Test::getTest($search['test_id']);

        $html_element['name'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['name']->setName('s_q_name');
        $html_element['name']->setId('s_q_name');
        $html_element['name']->setCaption('Вопрос');
        $html_element['name']->setConfig('type', 'text');
        $html_element['name']->setConfig('class', 'uk-width-1-1');
        $html_element['name']->setConfig('placeholder', 'Вопрос');
        $html_element['name']->setValueFromRequest();

        if (isset($search['test_id']) && $search['test_id'] != null)
        {
            if ($html_element['name']->getValue() != null)
            {
                $search['name'] = trim($html_element['name']->getValue());
            }

            $questions = Question::getQuestions($search);
            $total = Question::getTotalQuestions($search);
        }


        if ($is_can)
        {
            $url_param .= 's_direction='.$search['direction_id'].'&s_name='.$search['test_name']
                .'&tid='.$search['test_id'].'&page='.$page.'&s_q_name='.$search['name'];

            include_once APP_VIEWS.'question/index.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }
}