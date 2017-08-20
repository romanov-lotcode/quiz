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

    public function actionAdd()
    {
        $user_right = parent::getUserRight();
        $app_validate = new App_Validate();
        $url_param = '';
        $is_can = false;
        $search = [];
        $page = 1;
        $errors = false;
        $date_time = new DateTime();
        $question = [];
        $question_types = Question::getQuestionTypes();
        $test = null;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_QUESTION)
            {
                $is_can = true;
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

        $url_param .= 's_direction='.$search['direction_id'].'&s_name='.$search['test_name']
            .'&tid='.$search['test_id'].'&page='.$page.'&s_q_name='.$search['name'];

        $test = Test::getTest($search['test_id']);

        $html_element['name'] = new \HTMLElement\HTMLTextTextareaElement();
        $html_element['name']->setName('name');
        $html_element['name']->setId('name');
        $html_element['name']->setMin(1);
        $html_element['name']->setMax(1000);
        $html_element['name']->setCaption('Вопрос');
        $html_element['name']->setConfig('rows', '5');
        $html_element['name']->setConfig('class', 'uk-width-1-1');
        $html_element['name']->setConfig('placeholder', 'Вопрос');
        $html_element['name']->setValueFromRequest();

        $html_element['number'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['number']->setName('number');
        $html_element['number']->setId('number');
        $html_element['number']->setValue(0);
        $html_element['number']->setMax(6);
        $html_element['number']->setCaption('Номер');
        $html_element['number']->setConfig('type', 'number');
        $html_element['number']->setConfig('min', '0');
        $html_element['number']->setConfig('max', '999999');
        $html_element['number']->setConfig('class', 'uk-width-1-4');
        $html_element['number']->setConfig('placeholder', 'Номер');
        $html_element['number']->setValueFromRequest();

        $option_question_type_selected = 0;
        $option_question_type = [];
        $optgroup_question_type = [];

        if (isset($_POST['question_type']))
        {
            $option_question_type_selected = $_POST['question_type'];
            $option_question_type_selected = intval($option_question_type_selected);
            if ($option_question_type_selected != 0
                && $option_question_type_selected != 1)
            {
                $option_question_type_selected = 0;
            }
        }

        $i = 0;
        $option_question_type[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_question_type[$i]->setValue(0);
        $option_question_type[$i]->setText('Один к одному');
        ($option_question_type_selected == $option_question_type[$i]->getValue())? $option_question_type[$i]->setSelected(true):'';

        $i = 1;
        $option_question_type[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_question_type[$i]->setValue(1);
        $option_question_type[$i]->setText('Один ко многим');
        ($option_question_type_selected == $option_question_type[$i]->getValue())? $option_question_type[$i]->setSelected(true):'';

        $html_element['question_type'] = new \HTMLElement\HTMLSelectElement();
        $html_element['question_type']->setCaption('Тип вопроса');
        $html_element['question_type']->setConfig('class', 'uk-width-1-2');
        $html_element['question_type']->setName('question_type');
        $html_element['question_type']->setId('question_type');
        $html_element['question_type']->setNecessarily(true);

        $html_element['explanation'] = new \HTMLElement\HTMLTextTextareaElement();
        $html_element['explanation']->setName('explanation');
        $html_element['explanation']->setId('explanation');
        $html_element['explanation']->setMax(1000);
        $html_element['explanation']->setCaption('Объяснение');
        $html_element['explanation']->setConfig('rows', '5');
        $html_element['explanation']->setConfig('class', 'uk-width-1-1');
        $html_element['explanation']->setConfig('placeholder', 'Объяснение');
        $html_element['explanation']->setValueFromRequest();

        $html_element['comment'] = new \HTMLElement\HTMLTextTextareaElement();
        $html_element['comment']->setName('comment');
        $html_element['comment']->setId('comment');
        $html_element['comment']->setMax(1000);
        $html_element['comment']->setCaption('Комментарий');
        $html_element['comment']->setConfig('rows', '5');
        $html_element['comment']->setConfig('class', 'uk-width-1-1');
        $html_element['comment']->setConfig('placeholder', 'Комментарий');
        $html_element['comment']->setValueFromRequest();

        $html_element['hour'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['hour']->setName('hour');
        $html_element['hour']->setId('hour');
        $html_element['hour']->setValue(0);
        $html_element['hour']->setMax(3);
        $html_element['hour']->setCaption('часы');
        $html_element['hour']->setConfig('type', 'number');
        $html_element['hour']->setConfig('min', '0');
        $html_element['hour']->setConfig('max', '838');
        $html_element['hour']->setConfig('class', 'uk-width-1-2');
        $html_element['hour']->setConfig('placeholder', 'чч');
        $html_element['hour']->setValueFromRequest();

        $html_element['minute'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['minute']->setName('minute');
        $html_element['minute']->setId('minute');
        $html_element['minute']->setValue(0);
        $html_element['minute']->setMax(2);
        $html_element['minute']->setCaption('минуты');
        $html_element['minute']->setConfig('type', 'number');
        $html_element['minute']->setConfig('min', '0');
        $html_element['minute']->setConfig('max', '59');
        $html_element['minute']->setConfig('class', 'uk-width-1-2');
        $html_element['minute']->setConfig('placeholder', 'мм');
        $html_element['minute']->setValueFromRequest();

        $html_element['second'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['second']->setName('second');
        $html_element['second']->setId('second');
        $html_element['second']->setValue(0);
        $html_element['second']->setMax(2);
        $html_element['second']->setCaption('секунды');
        $html_element['second']->setConfig('type', 'number');
        $html_element['second']->setConfig('min', '0');
        $html_element['second']->setConfig('max', '59');
        $html_element['second']->setConfig('class', 'uk-width-1-2');
        $html_element['second']->setConfig('placeholder', 'сс');
        $html_element['second']->setValueFromRequest();

        $option_question_time_flag_select = APP_NO;
        $option_question_time_flag = [];
        $optgroup_question_time_flag = [];

        if (isset($_POST['question_time_flag']))
        {
            $option_question_time_flag_select = $_POST['question_time_flag'];
            $option_question_time_flag_select = intval($option_question_time_flag_select);
            if ($option_question_time_flag_select != APP_NO
                && $option_question_time_flag_select != APP_YES)
            {
                $option_question_time_flag_select = APP_NO;
            }
        }

        $i = 0;
        $option_question_time_flag[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_question_time_flag[$i]->setValue(APP_YES);
        $option_question_time_flag[$i]->setText('Да');
        ($option_question_time_flag_select == $option_question_time_flag[$i]->getValue())? $option_question_time_flag[$i]->setSelected(true):'';

        $i = 1;
        $option_question_time_flag[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_question_time_flag[$i]->setValue(APP_NO);
        $option_question_time_flag[$i]->setText('Нет');
        ($option_question_time_flag_select == $option_question_time_flag[$i]->getValue())? $option_question_time_flag[$i]->setSelected(true):'';

        $html_element['question_time_flag'] = new \HTMLElement\HTMLSelectElement();
        $html_element['question_time_flag']->setCaption('Включить время');
        $html_element['question_time_flag']->setConfig('class', 'uk-width-1-4');
        $html_element['question_time_flag']->setConfig('onchange', "show_hide('question_time_flag', 'q_time');");
        $html_element['question_time_flag']->setName('question_time_flag');
        $html_element['question_time_flag']->setId('question_time_flag');
        $html_element['question_time_flag']->setNecessarily(true);

        $option_flag_select = FLAG_OFF;
        $option_flag = [];
        $optgroup_flag = [];

        if (isset($_POST['flag']))
        {
            $option_flag_select = $_POST['flag'];
            $option_flag_select = intval($option_flag_select);
            if ($option_flag_select != FLAG_OFF
                && $option_flag_select != FLAG_ON)
            {
                $option_flag_select = FLAG_OFF;
            }
        }

        $i = 0;
        $option_flag[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_flag[$i]->setValue(FLAG_ON);
        $option_flag[$i]->setText('Вкл');
        ($option_flag_select == $option_flag[$i]->getValue())? $option_flag[$i]->setSelected(true):'';

        $i = 1;
        $option_flag[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_flag[$i]->setValue(FLAG_OFF);
        $option_flag[$i]->setText('Выкл');
        ($option_flag_select == $option_flag[$i]->getValue())? $option_flag[$i]->setSelected(true):'';

        $html_element['flag'] = new \HTMLElement\HTMLSelectElement();
        $html_element['flag']->setCaption('Состояние');
        $html_element['flag']->setConfig('class', 'uk-width-1-4');
        $html_element['flag']->setName('flag');
        $html_element['flag']->setId('flag');

        if (isset($_POST['path_img']))
        {

        }

        if (isset($_POST['add']))
        {
            print_r($_FILES['path_img']);

            if(is_uploaded_file($_FILES['path_img']['tmp_name']))
            {
                var_dump(move_uploaded_file($_FILES['path_img']['name'], ROOT.'/temp/'));
            }
            /*$html_element['name']->setValue($html_element['name']->getValue());
            $html_element['testing_count']->setValue($html_element['testing_count']->getValue());
            $html_element['question_count']->setValue($html_element['question_count']->getValue());
            $html_element['minimum_score']->setValue($html_element['minimum_score']->getValue());

            $html_element['name']->check();
            $html_element['testing_count']->check();
            $html_element['question_count']->check();
            $html_element['minimum_score']->check();

            if ($option_testing_time_flag_select == APP_YES)
            {
                $html_element['hour']->setValue(trim(intval($html_element['hour']->getValue())));
                $html_element['minute']->setValue(trim(intval($html_element['minute']->getValue())));
                $html_element['second']->setValue(trim(intval($html_element['second']->getValue())));

                $html_element['hour']->check();
                $html_element['minute']->check();
                $html_element['second']->check();

                if ($html_element['hour']->getValue() == 0
                    && $html_element['minute']->getValue() == 0
                    && $html_element['second']->getValue() == 0)
                {
                    $errors['testing_time'] = 'Вы включили время, но время прохождения не задали.<br />Укажите часы или минуты, или секунды.';
                    $html_element['hour']->setCheck(false);
                    $html_element['minute']->setCheck(false);
                    $html_element['second']->setCheck(false);
                }
            }

            if (!$html_element['name']->getCheck())
            {
                $errors['name'] = 'Ошибка в поле "'.$html_element['name']->getCaption().'".<br />Не может быть такой длины.';
            }

            if (!$app_validate->checkInt($html_element['testing_count']->getValue(), false, true, 1, 999999))
            {
                $html_element['testing_count']->setCheck(false);
            }

            if (!$html_element['testing_count']->getCheck())
            {
                $errors['testing_count'] = 'Ошибка в поле "'.$html_element['testing_count']->getCaption().'".<br />Должно быть целым числом от 1 до 999999.';
            }

            if (!$app_validate->checkInt($html_element['question_count']->getValue(), false, true, 1, 999999))
            {
                $html_element['question_count']->setCheck(false);
            }

            if (!$html_element['question_count']->getCheck())
            {
                $errors['question_count'] = 'Ошибка в поле "'.$html_element['question_count']->getCaption().'".<br />Должно быть целым числом от 1 до 999999.';
            }

            if (!$app_validate->checkInt($html_element['minimum_score']->getValue(), false, true, 1, 999999))
            {
                $html_element['minimum_score']->setCheck(false);
            }

            if (!$html_element['minimum_score']->getCheck())
            {
                $errors['minimum_score'] = 'Ошибка в поле "'.$html_element['minimum_score']->getCaption().'".<br />Должно быть целым числом от 1 до 999999.';
            }

            if (!$app_validate->checkInt($html_element['hour']->getValue(), true, true, 0, 838))
            {
                $html_element['hour']->setCheck(false);
            }

            if (!$html_element['hour']->getCheck())
            {
                $errors['hour'] = 'Ошибка в поле "'.$html_element['hour']->getCaption().'".<br />Должно быть целым числом от 0 до 838.';
            }

            if (!$app_validate->checkInt($html_element['minute']->getValue(), true, true, 0, 59))
            {
                $html_element['minute']->setCheck(false);
            }

            if (!$html_element['minute']->getCheck())
            {
                $errors['minute'] = 'Ошибка в поле "'.$html_element['minute']->getCaption().'".<br />Должно быть целым числом от 0 до 59.';
            }

            if (!$app_validate->checkInt($html_element['second']->getValue(), true, true, 0, 59))
            {
                $html_element['second']->setCheck(false);
            }

            if (!$html_element['second']->getCheck())
            {
                $errors['second'] = 'Ошибка в поле "'.$html_element['second']->getCaption().'".<br />Должно быть целым числом от 0 до 59.';
            }*/

            /*if ($errors === false)
            {
                $time['hour'] = $html_element['hour']->getValue();
                $time['minute'] = $html_element['minute']->getValue();
                $time['second'] = $html_element['second']->getValue();
                $testing['testing_time'] = $app_validate->getTimeFromArrayInt($time);
                if (!$testing['testing_time'])
                {
                    $errors['testing_time'] = 'Не удалось установить время';
                }
                if ($errors === false)
                {
                    $testing['name'] = $html_element['name']->getValue();
                    $testing['test_id'] = $search['test_id'];
                    $testing['testing_count'] = $html_element['testing_count']->getValue();
                    $testing['question_count'] = $html_element['question_count']->getValue();
                    $testing['is_question_random'] = $option_is_question_random_select;
                    $testing['is_answer_random'] = $option_is_answer_random_select;
                    $testing['minimum_score'] = $html_element['minimum_score']->getValue();
                    // testing_time значение передано выше
                    $testing['testing_time_flag'] = $option_testing_time_flag_select;
                    $testing['change_user_id'] = User::checkLogged();
                    $testing['change_datetime'] = $date_time->format('Y-m-d H:i:s');
                    $testing['flag'] = $option_flag_select;
                    $is_add = Testing::add($testing);
                    if ($is_add !== false)
                    {
                        header('Location: /testing/index?'.$url_param);
                    }
                    else
                    {
                        $errors['add'] = 'Ничего не удалось добавить! Возможно у вас нет прав';
                    }
                }
            }*/
        }

        if ($is_can)
        {
            include_once APP_VIEWS.'question/add.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }
}