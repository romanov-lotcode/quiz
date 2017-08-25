<?php


class AnswerController extends BaseController
{
    public function actionIndex()
    {
        $user_right = parent::getUserRight();
        $app_state = new App_State();
        $url_param = '';
        $is_can = false;
        $is_can_question = false;
        $search = [];
        $page = 1;
        $index_number = 0;
        $answers = [];
        $total = 0;
        $question = null;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_QUESTION)
            {
                $is_can_question = true;
            }
            if ($u_r['right_name'] == CAN_MODERATOR_ANSWER)
            {
                $is_can = true;
            }
            if ($is_can === true && $is_can_question === true)
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

        if (isset($_GET['qid']))
        {
            $search['question_id'] = htmlspecialchars($_GET['qid']);
        }

        $question = Question::getQuestion($search['question_id']);
        $answers = Answer::getAnswers($search);
        $total = Answer::getTotalAnswers($search);

        if ($is_can)
        {
            $url_param .= 's_direction='.$search['direction_id'].'&s_name='.$search['test_name']
                .'&tid='.$search['test_id'].'&page='.$page.'&s_q_name='.$search['name']
                .'&qid='.$search['question_id'];

            include_once APP_VIEWS.'answer/index.php';
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
        $answer = [];
        $question = [];
        $errors = false;
        $date_time = new DateTime();

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_ANSWER)
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

        if (isset($_GET['qid']))
        {
            $search['question_id'] = htmlspecialchars($_GET['qid']);
        }

        $question = Question::getQuestion($search['question_id']);

        $url_param .= 's_direction='.$search['direction_id'].'&s_name='.$search['test_name']
            .'&tid='.$search['test_id'].'&page='.$page.'&s_q_name='.$search['name']
            .'&qid='.$search['question_id'];

        $html_element['name'] = new \HTMLElement\HTMLTextTextareaElement();
        $html_element['name']->setName('name');
        $html_element['name']->setId('name');
        $html_element['name']->setMin(1);
        $html_element['name']->setMax(1000);
        $html_element['name']->setCaption('Ответ');
        $html_element['name']->setConfig('rows', '5');
        $html_element['name']->setConfig('class', 'uk-width-1-1');
        $html_element['name']->setConfig('placeholder', 'Ответ');
        $html_element['name']->setValueFromRequest();

        $html_element['complexity_coefficient'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['complexity_coefficient']->setName('complexity_coefficient');
        $html_element['complexity_coefficient']->setId('complexity_coefficient');
        $html_element['complexity_coefficient']->setValue(0);
        $html_element['complexity_coefficient']->setMin(1);
        $html_element['complexity_coefficient']->setMax(6);
        $html_element['complexity_coefficient']->setCaption('Коэф. сложности');
        $html_element['complexity_coefficient']->setConfig('type', 'number');
        $html_element['complexity_coefficient']->setConfig('min', '-99999');
        $html_element['complexity_coefficient']->setConfig('max', '999999');
        $html_element['complexity_coefficient']->setConfig('class', 'uk-width-1-4');
        $html_element['complexity_coefficient']->setConfig('placeholder', 'Коэф. сложности');
        $html_element['complexity_coefficient']->setValueFromRequest();

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

        if (isset($_POST['add']))
        {
            $html_element['name']->setValue($html_element['name']->getValue());
            $html_element['complexity_coefficient']->setValue($html_element['complexity_coefficient']->getValue());

            $html_element['name']->check();
            $html_element['complexity_coefficient']->check();

            if (!$html_element['name']->getCheck())
            {
                $errors['name'] = 'Ошибка в поле "'.$html_element['name']->getCaption().'".<br />Не может быть такой длины.';
            }

            if (!$app_validate->checkInt($html_element['complexity_coefficient']->getValue(), true, true, -99999, 999999))
            {
                $html_element['complexity_coefficient']->setCheck(false);
            }

            if (!$html_element['complexity_coefficient']->getCheck())
            {
                $errors['complexity_coefficient'] = 'Ошибка в поле "'.$html_element['complexity_coefficient']->getCaption().'".<br />Должно быть целым числом от -999999 до 999999.';
            }

            $search['question_id'] = intval($search['question_id']);
            if ($search['question_id'] < 1)
            {
                $errors['question_id'] = 'Не удалось определить вопрос';
            }

            if ($errors === false)
            {
                $answer['name'] = $html_element['name']->getValue();
                $answer['question_id'] = $search['question_id'];
                $answer['complexity_coefficient'] = $html_element['complexity_coefficient']->getValue();
                $answer['change_user_id'] = User::checkLogged();
                $answer['change_datetime'] = $date_time->format('Y-m-d H:i:s');
                $answer['flag'] = $option_flag_select;
                $is_add = Answer::add($answer);
                if ($is_add !== false)
                {
                    header('Location: /answer/index?'.$url_param);
                }
                else
                {
                    $errors['add'] = 'Ничего не удалось добавить! Возможно у вас нет прав';
                }
            }
        }

        if ($is_can)
        {
            include_once APP_VIEWS.'answer/add.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }

    public function actionEdit()
    {
        $user_right = parent::getUserRight();
        $app_validate = new App_Validate();
        $url_param = '';
        $is_can = false;
        $search = [];
        $answer = [];
        $errors = false;
        $date_time = new DateTime();
        $aid = null;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_ANSWER)
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

        if (isset($_GET['qid']))
        {
            $search['question_id'] = htmlspecialchars($_GET['qid']);
        }

        if (isset($_GET['aid']))
        {
            $aid = htmlspecialchars($_GET['aid']);
        }

        $answer = Answer::getAnswer($aid);

        $url_param .= 's_direction='.$search['direction_id'].'&s_name='.$search['test_name']
            .'&tid='.$search['test_id'].'&page='.$page.'&s_q_name='.$search['name']
            .'&qid='.$search['question_id'];

        $html_element['name'] = new \HTMLElement\HTMLTextTextareaElement();
        $html_element['name']->setName('name');
        $html_element['name']->setId('name');
        $html_element['name']->setValue($answer['name']);
        $html_element['name']->setMin(1);
        $html_element['name']->setMax(1000);
        $html_element['name']->setCaption('Ответ');
        $html_element['name']->setConfig('rows', '5');
        $html_element['name']->setConfig('class', 'uk-width-1-1');
        $html_element['name']->setConfig('placeholder', 'Ответ');
        $html_element['name']->setValueFromRequest();

        $html_element['complexity_coefficient'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['complexity_coefficient']->setName('complexity_coefficient');
        $html_element['complexity_coefficient']->setId('complexity_coefficient');
        $html_element['complexity_coefficient']->setValue($answer['complexity_coefficient']);
        $html_element['complexity_coefficient']->setMin(1);
        $html_element['complexity_coefficient']->setMax(6);
        $html_element['complexity_coefficient']->setCaption('Коэф. сложности');
        $html_element['complexity_coefficient']->setConfig('type', 'number');
        $html_element['complexity_coefficient']->setConfig('min', '-99999');
        $html_element['complexity_coefficient']->setConfig('max', '999999');
        $html_element['complexity_coefficient']->setConfig('class', 'uk-width-1-4');
        $html_element['complexity_coefficient']->setConfig('placeholder', 'Коэф. сложности');
        $html_element['complexity_coefficient']->setValueFromRequest();

        $option_flag_select = $answer['flag'];
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

        if ($answer['flag'] == FLAG_NO_CHANGE)
        {
            $errors['no_change'] = 'Невозможно изменить данный ответ';
            $html_element['name']->setConfig('disabled', 'disabled');
            $html_element['complexity_coefficient']->setConfig('disabled', 'disabled');
            $html_element['flag']->setConfig('disabled', 'disabled');
            $option_flag_select = FLAG_NO_CHANGE;
        }

        if (isset($_POST['edit']))
        {
            if ($aid != $answer['id'])
            {
                $errors['id'] = 'Невозможно внести изменения для данного овета';
            }

            $html_element['name']->setValue($html_element['name']->getValue());
            $html_element['complexity_coefficient']->setValue($html_element['complexity_coefficient']->getValue());

            $html_element['name']->check();
            $html_element['complexity_coefficient']->check();

            if (!$html_element['name']->getCheck())
            {
                $errors['name'] = 'Ошибка в поле "'.$html_element['name']->getCaption().'".<br />Не может быть такой длины.';
            }

            if (!$app_validate->checkInt($html_element['complexity_coefficient']->getValue(), true, true, -99999, 999999))
            {
                $html_element['complexity_coefficient']->setCheck(false);
            }

            if (!$html_element['complexity_coefficient']->getCheck())
            {
                $errors['complexity_coefficient'] = 'Ошибка в поле "'.$html_element['complexity_coefficient']->getCaption().'".<br />Должно быть целым числом от -999999 до 999999.';
            }

            if ($errors === false)
            {
                $answer['name'] = $html_element['name']->getValue();
                $answer['complexity_coefficient'] = $html_element['complexity_coefficient']->getValue();
                $answer['change_user_id'] = User::checkLogged();
                $answer['change_datetime'] = $date_time->format('Y-m-d H:i:s');
                $answer['flag'] = $option_flag_select;
                Answer::edit($answer);
                header('Location: /answer/index?'.$url_param);
            }
        }

        if ($is_can)
        {
            include_once APP_VIEWS.'answer/edit.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }

    public function actionDelete()
    {
        $user_right = parent::getUserRight();
        $url_param = '';
        $is_can = false;
        $search = [];
        $answer = [];
        $errors = false;
        $date_time = new DateTime();
        $aid = null;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_ANSWER)
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

        if (isset($_GET['qid']))
        {
            $search['question_id'] = htmlspecialchars($_GET['qid']);
        }

        if (isset($_GET['aid']))
        {
            $aid = htmlspecialchars($_GET['aid']);
        }

        $answer = Answer::getAnswer($aid);

        $url_param .= 's_direction='.$search['direction_id'].'&s_name='.$search['test_name']
            .'&tid='.$search['test_id'].'&page='.$page.'&s_q_name='.$search['name']
            .'&qid='.$search['question_id'];


        if ($answer['flag'] == FLAG_NO_CHANGE)
        {
            $errors['no_change'] = 'Невозможно изменить данный ответ';
        }

        if (isset($_POST['yes']))
        {
            if ($aid != $answer['id'])
            {
                $errors['id'] = 'Невозможно внести изменения для данного ответа';
            }

            if ($errors === false)
            {
                $answer['change_user_id'] = User::checkLogged();
                $answer['change_datetime'] = $date_time->format('Y-m-d H:i:s');
                Answer::delete($answer);
                header('Location: /answer/index?'.$url_param);
            }
        }

        if (isset($_POST['no']))
        {
            header('Location: /answer/index?'.$url_param);
        }

        if ($is_can)
        {
            include_once APP_VIEWS.'answer/delete.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }
}