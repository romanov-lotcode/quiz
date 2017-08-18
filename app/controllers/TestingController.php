<?php

class TestingController extends BaseController
{
    public function actionIndex()
    {
        $user_right = parent::getUserRight();
        $app_state = new App_State();
        $url_param = '';
        $is_can = false;
        $search = [];
        $page = 1;
        $index_number = 1;
        $directions = [];
        $option_direction_selected = null;
        $tests = [];
        $option_test_selected = null;
        $testing_list = [];
        $total = 0;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_TEST)
            {
                $is_can = true;
                break;
            }
        }

        if (isset($_GET['s_direction']))
        {
            $option_direction_selected = htmlspecialchars($_GET['s_direction']);

        }

        if (isset($_GET['s_test']))
        {
            $option_test_selected = htmlspecialchars($_GET['s_test']);
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }

        if ($page < 1)
        {
            $page = 1;
        }

        $directions = Direction::getDirectionsByState(STATE_ON);
        $option_direction = [];
        $optgroup_direction = [];

        $i = 0;
        $option_direction[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_direction[$i]->setValue(0);
        $option_direction[$i]->setText('[выбрать]');

        $i = 1;

        foreach ($directions as $value)
        {
            $option_direction[$i] = new \HTMLElement\HTMLSelectOptionElement();
            $option_direction[$i]->setValue($value['id']);
            $option_direction[$i]->setText($value['name']);

            if ($option_direction_selected == $option_direction[$i]->getValue())
            {
                $option_direction[$i]->setSelected(true);
            }

            $i++;
        }

        $html_element['direction'] = new \HTMLElement\HTMLSelectElement();
        $html_element['direction']->setCaption('Направление');
        $html_element['direction']->setName('s_direction');
        $html_element['direction']->setId('s_direction');
        $html_element['direction']->setConfig('data-placeholder', 'Не выбрано');
        $html_element['direction']->setConfig('onchange', 'this.form.submit();');
        $html_element['direction']->setConfig('class', 'uk-width-1-1');

        $tests = Test::getTestsByDirectionAndState($option_direction_selected, STATE_ON);
        $option_test = [];
        $optgroup_test = [];

        $i = 0;
        $option_test[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_test[$i]->setValue(0);
        $option_test[$i]->setText('[выбрать]');

        $i = 1;

        foreach ($tests as $value)
        {
            $option_test[$i] = new \HTMLElement\HTMLSelectOptionElement();
            $option_test[$i]->setValue($value['id']);
            $option_test[$i]->setText($value['name']);

            if ($option_test_selected == $option_test[$i]->getValue())
            {
                $option_test[$i]->setSelected(true);
            }

            $i++;
        }

        $html_element['test'] = new \HTMLElement\HTMLSelectElement();
        $html_element['test']->setCaption('Тест');
        $html_element['test']->setName('s_test');
        $html_element['test']->setId('s_test');
        $html_element['test']->setConfig('data-placeholder', 'Не выбрано');
        $html_element['test']->setConfig('onchange', 'this.form.submit();');
        $html_element['test']->setConfig('class', 'uk-width-1-1');

        $html_element['name'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['name']->setName('s_name');
        $html_element['name']->setId('s_name');
        $html_element['name']->setCaption('Тестирование');
        $html_element['name']->setConfig('type', 'text');
        $html_element['name']->setConfig('class', 'uk-width-1-1');
        $html_element['name']->setConfig('placeholder', 'Тестирование');
        $html_element['name']->setValueFromRequest();



        if ($option_direction_selected > 0 && $option_test_selected > 0)
        {
            $search['direction_id'] = $option_direction_selected;
            $search['test_id'] = $option_test_selected;
            if ($html_element['name']->getValue() != null)
            {
                $search['name'] = trim($html_element['name']->getValue());
            }
            $testing_list = Testing::getTestingList($search, $page);
            $total = Testing::getTotalTestingList($search);
            $index_number = Testing::getIndexNumber($page);
            $pagination = new Pagination($total, $page, Testing::SHOW_BY_DEFAULT, 'page=');
        }

        if ($is_can)
        {
            $url_param .= 's_direction='.$search['direction_id'].'&s_test='.$search['test_id']
                .'&s_name='.$search['name'].'&page='.$page;

            include_once APP_VIEWS.'testing/index.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }

    public function actionAdd()
    {
        $user_right = parent::getUserRight();
        $app_state = new App_State();
        $app_validate = new App_Validate();
        $url_param = '';
        $is_can = false;
        $search = [];
        $page = 1;
        $errors = false;
        $date_time = new DateTime();
        $testing = [];
        $test = null;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_DIRECTION)
            {
                $is_can = true;
                break;
            }
        }

        if (isset($_GET['s_direction']))
        {
            $search['direction_id'] = htmlspecialchars($_GET['s_direction']);
        }

        if (isset($_GET['s_test']))
        {
            $search['test_id'] = htmlspecialchars($_GET['s_test']);
            $test = Test::getTest($search['test_id']);
        }

        if (isset($_GET['s_name']))
        {
            $search['name'] = htmlspecialchars($_GET['s_name']);
        }

        if (isset($_GET['page']))
        {
            $page = intval(htmlspecialchars($_GET['page']));
            if ($page < 1)
            {
                $page = 1;
            }
        }

        $url_param .= 's_direction='.$search['direction_id'].'&s_test='.$search['test_id']
            .'&s_name='.$search['name'].'&page='.$page;

        if ($test['name'] == null || ($test['flag'] != 0 && $test['flag'] != 1))
        {
            $errors['test_id'] = 'Ошибка. Не выбран тест.';
        }

        $html_element['name'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['name']->setName('name');
        $html_element['name']->setId('name');
        $html_element['name']->setMin(1);
        $html_element['name']->setMax(500);
        $html_element['name']->setCaption('Наименование');
        $html_element['name']->setConfig('type', 'text');
        $html_element['name']->setConfig('class', 'uk-width-1-1');
        $html_element['name']->setConfig('placeholder', 'Тестирование');
        $html_element['name']->setValueFromRequest();

        $html_element['testing_count'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['testing_count']->setName('testing_count');
        $html_element['testing_count']->setId('testing_count');
        $html_element['testing_count']->setValue(1);
        $html_element['testing_count']->setMin(1);
        $html_element['testing_count']->setMax(6);
        $html_element['testing_count']->setCaption('Количество прохождений');
        $html_element['testing_count']->setConfig('type', 'number');
        $html_element['testing_count']->setConfig('min', '1');
        $html_element['testing_count']->setConfig('max', '999999');
        $html_element['testing_count']->setConfig('class', 'uk-width-1-4');
        $html_element['testing_count']->setConfig('placeholder', 'Количество прохождений');
        $html_element['testing_count']->setValueFromRequest();

        $html_element['question_count'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['question_count']->setName('question_count');
        $html_element['question_count']->setId('question_count');
        $html_element['question_count']->setValue(1);
        $html_element['question_count']->setMin(1);
        $html_element['question_count']->setMax(6);
        $html_element['question_count']->setCaption('Количество вопросов');
        $html_element['question_count']->setConfig('type', 'number');
        $html_element['question_count']->setConfig('min', '1');
        $html_element['question_count']->setConfig('max', '999999');
        $html_element['question_count']->setConfig('class', 'uk-width-1-4');
        $html_element['question_count']->setConfig('placeholder', 'Количество вопросов');
        $html_element['question_count']->setValueFromRequest();

        $option_is_question_random_select = APP_NO;
        $option_is_question_random = [];
        $optgroup_is_question_random = [];

        if (isset($_POST['is_question_random']))
        {
            $option_is_question_random_select = $_POST['is_question_random'];
            $option_is_question_random_select = intval($option_is_question_random_select);
            if ($option_is_question_random_select != APP_NO
                && $option_is_question_random_select != APP_YES)
            {
                $option_is_question_random_select = APP_NO;
            }
        }

        $i = 0;
        $option_is_question_random[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_is_question_random[$i]->setValue(APP_YES);
        $option_is_question_random[$i]->setText('Да');
        ($option_is_question_random_select == $option_is_question_random[$i]->getValue())? $option_is_question_random[$i]->setSelected(true):'';

        $i = 1;
        $option_is_question_random[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_is_question_random[$i]->setValue(APP_NO);
        $option_is_question_random[$i]->setText('Нет');
        ($option_is_question_random_select == $option_is_question_random[$i]->getValue())? $option_is_question_random[$i]->setSelected(true):'';

        $html_element['is_question_random'] = new \HTMLElement\HTMLSelectElement();
        $html_element['is_question_random']->setCaption('Выводить вопросы случайно');
        $html_element['is_question_random']->setConfig('class', 'uk-width-1-4');
        $html_element['is_question_random']->setName('is_question_random');
        $html_element['is_question_random']->setId('is_question_random');
        $html_element['is_question_random']->setNecessarily(true);

        $option_is_answer_random_select = APP_NO;
        $option_is_answer_random = [];
        $optgroup_is_answer_random = [];

        if (isset($_POST['is_answer_random']))
        {
            $option_is_answer_random_select = $_POST['is_answer_random'];
            $option_is_answer_random_select = intval($option_is_answer_random_select);
            if ($option_is_answer_random_select != APP_NO
                && $option_is_answer_random_select != APP_YES)
            {
                $option_is_answer_random_select = APP_NO;
            }
        }

        $i = 0;
        $option_is_answer_random[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_is_answer_random[$i]->setValue(APP_YES);
        $option_is_answer_random[$i]->setText('Да');
        ($option_is_answer_random_select == $option_is_answer_random[$i]->getValue())? $option_is_answer_random[$i]->setSelected(true):'';

        $i = 1;
        $option_is_answer_random[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_is_answer_random[$i]->setValue(APP_NO);
        $option_is_answer_random[$i]->setText('Нет');
        ($option_is_answer_random_select == $option_is_answer_random[$i]->getValue())? $option_is_answer_random[$i]->setSelected(true):'';

        $html_element['is_answer_random'] = new \HTMLElement\HTMLSelectElement();
        $html_element['is_answer_random']->setCaption('Выводить ответы случайно');
        $html_element['is_answer_random']->setConfig('class', 'uk-width-1-4');
        $html_element['is_answer_random']->setName('is_answer_random');
        $html_element['is_answer_random']->setId('is_answer_random');
        $html_element['is_answer_random']->setNecessarily(true);

        $html_element['minimum_score'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['minimum_score']->setName('minimum_score');
        $html_element['minimum_score']->setId('minimum_score');
        $html_element['minimum_score']->setValue(1);
        $html_element['minimum_score']->setMin(1);
        $html_element['minimum_score']->setMax(6);
        $html_element['minimum_score']->setCaption('Коэффициент прохождения');
        $html_element['minimum_score']->setConfig('type', 'number');
        $html_element['minimum_score']->setConfig('min', '1');
        $html_element['minimum_score']->setConfig('max', '999999');
        $html_element['minimum_score']->setConfig('class', 'uk-width-1-4');
        $html_element['minimum_score']->setConfig('placeholder', 'Коэффициент прохождения');
        $html_element['minimum_score']->setValueFromRequest();

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

        $option_testing_time_flag_select = APP_NO;
        $option_testing_time_flag = [];
        $optgroup_testing_time_flag = [];

        if (isset($_POST['testing_time_flag']))
        {
            $option_testing_time_flag_select = $_POST['testing_time_flag'];
            $option_testing_time_flag_select = intval($option_testing_time_flag_select);
            if ($option_testing_time_flag_select != APP_NO
                && $option_testing_time_flag_select != APP_YES)
            {
                $option_testing_time_flag_select = APP_NO;
            }
        }

        $i = 0;
        $option_testing_time_flag[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_testing_time_flag[$i]->setValue(APP_YES);
        $option_testing_time_flag[$i]->setText('Да');
        ($option_testing_time_flag_select == $option_testing_time_flag[$i]->getValue())? $option_testing_time_flag[$i]->setSelected(true):'';

        $i = 1;
        $option_testing_time_flag[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_testing_time_flag[$i]->setValue(APP_NO);
        $option_testing_time_flag[$i]->setText('Нет');
        ($option_testing_time_flag_select == $option_testing_time_flag[$i]->getValue())? $option_testing_time_flag[$i]->setSelected(true):'';

        $html_element['testing_time_flag'] = new \HTMLElement\HTMLSelectElement();
        $html_element['testing_time_flag']->setCaption('Включить время');
        $html_element['testing_time_flag']->setConfig('class', 'uk-width-1-4');
        $html_element['testing_time_flag']->setConfig('onchange', "show_hide('testing_time_flag', 't_time');");
        $html_element['testing_time_flag']->setName('testing_time_flag');
        $html_element['testing_time_flag']->setId('testing_time_flag');
        $html_element['testing_time_flag']->setNecessarily(true);

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
            }

            if ($errors === false)
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
            }
        }

        if ($is_can)
        {
            include_once APP_VIEWS.'testing/add.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }

    public function actionEdit()
    {
        $user_right = parent::getUserRight();
        $app_state = new App_State();
        $app_validate = new App_Validate();
        $url_param = '';
        $is_can = false;
        $search = [];
        $page = 1;
        $errors = false;
        $date_time = new DateTime();
        $testing = [];
        $tid = null;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_DIRECTION)
            {
                $is_can = true;
                break;
            }
        }

        if (isset($_GET['s_direction']))
        {
            $search['direction_id'] = htmlspecialchars($_GET['s_direction']);
        }

        if (isset($_GET['s_test']))
        {
            $search['test_id'] = htmlspecialchars($_GET['s_test']);
        }

        if (isset($_GET['s_name']))
        {
            $search['name'] = htmlspecialchars($_GET['s_name']);
        }

        if (isset($_GET['page']))
        {
            $page = intval(htmlspecialchars($_GET['page']));
            if ($page < 1)
            {
                $page = 1;
            }
        }

        if (isset($_GET['tid']))
        {
            $tid = htmlspecialchars($_GET['tid']);
        }

        $url_param .= 's_direction='.$search['direction_id'].'&s_test='.$search['test_id']
            .'&s_name='.$search['name'].'&page='.$page;

        $testing = Testing::getTesting($tid);
        if ($testing['test_name'] == null || ($testing['test_flag'] != 0 && $testing['test_flag'] != 1))
        {
            $errors['test_id'] = 'Ошибка. Не выбран тест.';
        }

        $html_element['name'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['name']->setName('name');
        $html_element['name']->setId('name');
        $html_element['name']->setValue($testing['name']);
        $html_element['name']->setMin(1);
        $html_element['name']->setMax(500);
        $html_element['name']->setCaption('Наименование');
        $html_element['name']->setConfig('type', 'text');
        $html_element['name']->setConfig('class', 'uk-width-1-1');
        $html_element['name']->setConfig('placeholder', 'Тестирование');
        $html_element['name']->setValueFromRequest();

        $html_element['testing_count'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['testing_count']->setName('testing_count');
        $html_element['testing_count']->setId('testing_count');
        $html_element['testing_count']->setValue($testing['testing_count']);
        $html_element['testing_count']->setMin(1);
        $html_element['testing_count']->setMax(6);
        $html_element['testing_count']->setCaption('Количество прохождений');
        $html_element['testing_count']->setConfig('type', 'number');
        $html_element['testing_count']->setConfig('min', '1');
        $html_element['testing_count']->setConfig('max', '999999');
        $html_element['testing_count']->setConfig('class', 'uk-width-1-4');
        $html_element['testing_count']->setConfig('placeholder', 'Количество прохождений');
        $html_element['testing_count']->setValueFromRequest();

        $html_element['question_count'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['question_count']->setName('question_count');
        $html_element['question_count']->setId('question_count');
        $html_element['question_count']->setValue($testing['question_count']);
        $html_element['question_count']->setMin(1);
        $html_element['question_count']->setMax(6);
        $html_element['question_count']->setCaption('Количество вопросов');
        $html_element['question_count']->setConfig('type', 'number');
        $html_element['question_count']->setConfig('min', '1');
        $html_element['question_count']->setConfig('max', '999999');
        $html_element['question_count']->setConfig('class', 'uk-width-1-4');
        $html_element['question_count']->setConfig('placeholder', 'Количество вопросов');
        $html_element['question_count']->setValueFromRequest();

        $option_is_question_random_select = $testing['is_question_random'];
        $option_is_question_random = [];
        $optgroup_is_question_random = [];

        if (isset($_POST['is_question_random']))
        {
            $option_is_question_random_select = $_POST['is_question_random'];
            $option_is_question_random_select = intval($option_is_question_random_select);
            if ($option_is_question_random_select != APP_NO
                && $option_is_question_random_select != APP_YES)
            {
                $option_is_question_random_select = APP_NO;
            }
        }

        $i = 0;
        $option_is_question_random[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_is_question_random[$i]->setValue(APP_YES);
        $option_is_question_random[$i]->setText('Да');
        ($option_is_question_random_select == $option_is_question_random[$i]->getValue())? $option_is_question_random[$i]->setSelected(true):'';

        $i = 1;
        $option_is_question_random[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_is_question_random[$i]->setValue(APP_NO);
        $option_is_question_random[$i]->setText('Нет');
        ($option_is_question_random_select == $option_is_question_random[$i]->getValue())? $option_is_question_random[$i]->setSelected(true):'';

        $html_element['is_question_random'] = new \HTMLElement\HTMLSelectElement();
        $html_element['is_question_random']->setCaption('Выводить вопросы случайно');
        $html_element['is_question_random']->setConfig('class', 'uk-width-1-4');
        $html_element['is_question_random']->setName('is_question_random');
        $html_element['is_question_random']->setId('is_question_random');
        $html_element['is_question_random']->setNecessarily(true);

        $option_is_answer_random_select = $testing['is_answer_random'];
        $option_is_answer_random = [];
        $optgroup_is_answer_random = [];

        if (isset($_POST['is_answer_random']))
        {
            $option_is_answer_random_select = $_POST['is_answer_random'];
            $option_is_answer_random_select = intval($option_is_answer_random_select);
            if ($option_is_answer_random_select != APP_NO
                && $option_is_answer_random_select != APP_YES)
            {
                $option_is_answer_random_select = APP_NO;
            }
        }

        $i = 0;
        $option_is_answer_random[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_is_answer_random[$i]->setValue(APP_YES);
        $option_is_answer_random[$i]->setText('Да');
        ($option_is_answer_random_select == $option_is_answer_random[$i]->getValue())? $option_is_answer_random[$i]->setSelected(true):'';

        $i = 1;
        $option_is_answer_random[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_is_answer_random[$i]->setValue(APP_NO);
        $option_is_answer_random[$i]->setText('Нет');
        ($option_is_answer_random_select == $option_is_answer_random[$i]->getValue())? $option_is_answer_random[$i]->setSelected(true):'';

        $html_element['is_answer_random'] = new \HTMLElement\HTMLSelectElement();
        $html_element['is_answer_random']->setCaption('Выводить ответы случайно');
        $html_element['is_answer_random']->setConfig('class', 'uk-width-1-4');
        $html_element['is_answer_random']->setName('is_answer_random');
        $html_element['is_answer_random']->setId('is_answer_random');
        $html_element['is_answer_random']->setNecessarily(true);

        $html_element['minimum_score'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['minimum_score']->setName('minimum_score');
        $html_element['minimum_score']->setId('minimum_score');
        $html_element['minimum_score']->setValue($testing['minimum_score']);
        $html_element['minimum_score']->setMin(1);
        $html_element['minimum_score']->setMax(6);
        $html_element['minimum_score']->setCaption('Коэффициент прохождения');
        $html_element['minimum_score']->setConfig('type', 'number');
        $html_element['minimum_score']->setConfig('min', '1');
        $html_element['minimum_score']->setConfig('max', '999999');
        $html_element['minimum_score']->setConfig('class', 'uk-width-1-4');
        $html_element['minimum_score']->setConfig('placeholder', 'Коэффициент прохождения');
        $html_element['minimum_score']->setValueFromRequest();

        $time = $app_validate->setTimeArrayFromTime($testing['testing_time'], 'H:i:s');

        $html_element['hour'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['hour']->setName('hour');
        $html_element['hour']->setId('hour');
        $html_element['hour']->setMax(3);
        $html_element['hour']->setCaption('часы');
        $html_element['hour']->setConfig('type', 'number');
        $html_element['hour']->setConfig('min', '0');
        $html_element['hour']->setConfig('max', '838');
        $html_element['hour']->setValue($time['hour']);
        $html_element['hour']->setConfig('class', 'uk-width-1-2');
        $html_element['hour']->setConfig('placeholder', 'чч');
        $html_element['hour']->setValueFromRequest();

        $html_element['minute'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['minute']->setName('minute');
        $html_element['minute']->setId('minute');
        $html_element['minute']->setMax(2);
        $html_element['minute']->setCaption('минуты');
        $html_element['minute']->setConfig('type', 'number');
        $html_element['minute']->setConfig('min', '0');
        $html_element['minute']->setConfig('max', '59');
        $html_element['minute']->setValue($time['minute']);
        $html_element['minute']->setConfig('class', 'uk-width-1-2');
        $html_element['minute']->setConfig('placeholder', 'мм');
        $html_element['minute']->setValueFromRequest();

        $html_element['second'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['second']->setName('second');
        $html_element['second']->setId('second');
        $html_element['second']->setMax(2);
        $html_element['second']->setCaption('секунды');
        $html_element['second']->setConfig('type', 'number');
        $html_element['second']->setConfig('min', '0');
        $html_element['second']->setConfig('max', '59');
        $html_element['second']->setValue($time['second']);
        $html_element['second']->setConfig('class', 'uk-width-1-2');
        $html_element['second']->setConfig('placeholder', 'сс');
        $html_element['second']->setValueFromRequest();

        $option_testing_time_flag_select = $testing['testing_time_flag'];
        $option_testing_time_flag = [];
        $optgroup_testing_time_flag = [];

        if (isset($_POST['testing_time_flag']))
        {
            $option_testing_time_flag_select = $_POST['testing_time_flag'];
            $option_testing_time_flag_select = intval($option_testing_time_flag_select);
            if ($option_testing_time_flag_select != APP_NO
                && $option_testing_time_flag_select != APP_YES)
            {
                $option_testing_time_flag_select = APP_NO;
            }
        }

        $i = 0;
        $option_testing_time_flag[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_testing_time_flag[$i]->setValue(APP_YES);
        $option_testing_time_flag[$i]->setText('Да');
        ($option_testing_time_flag_select == $option_testing_time_flag[$i]->getValue())? $option_testing_time_flag[$i]->setSelected(true):'';

        $i = 1;
        $option_testing_time_flag[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_testing_time_flag[$i]->setValue(APP_NO);
        $option_testing_time_flag[$i]->setText('Нет');
        ($option_testing_time_flag_select == $option_testing_time_flag[$i]->getValue())? $option_testing_time_flag[$i]->setSelected(true):'';

        $html_element['testing_time_flag'] = new \HTMLElement\HTMLSelectElement();
        $html_element['testing_time_flag']->setCaption('Включить время');
        $html_element['testing_time_flag']->setConfig('class', 'uk-width-1-4');
        $html_element['testing_time_flag']->setConfig('onchange', "show_hide('testing_time_flag', 't_time');");
        $html_element['testing_time_flag']->setName('testing_time_flag');
        $html_element['testing_time_flag']->setId('testing_time_flag');
        $html_element['testing_time_flag']->setNecessarily(true);

        $option_flag_select = $testing['flag'];
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

        if ($testing['flag'] == FLAG_NO_CHANGE)
        {
            $errors['no_change'] = 'Невозможно изменить данное тестирование';
            $html_element['name']->setConfig('disabled', 'disabled');
            $html_element['testing_count']->setConfig('disabled', 'disabled');
            $html_element['question_count']->setConfig('disabled', 'disabled');
            $html_element['is_question_random']->setConfig('disabled', 'disabled');
            $html_element['is_answer_random']->setConfig('disabled', 'disabled');
            $html_element['minimum_score']->setConfig('disabled', 'disabled');
            $html_element['hour']->setConfig('disabled', 'disabled');
            $html_element['minute']->setConfig('disabled', 'disabled');
            $html_element['second']->setConfig('disabled', 'disabled');
            $html_element['testing_time_flag']->setConfig('disabled', 'disabled');
            $html_element['flag']->setConfig('disabled', 'disabled');
            $option_flag_select = FLAG_NO_CHANGE;
        }

        if (isset($_POST['edit']))
        {
            $html_element['name']->setValue($html_element['name']->getValue());
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
            }

            if ($tid != $testing['id'])
            {
                $errors['id'] = 'Невозможно внести изменения для данного тестирования';
            }

            if ($errors === false)
            {
                $time = null;
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
                    if ($testing['flag'] != FLAG_NO_CHANGE)
                    {
                        Testing::edit($testing);
                        header('Location: /testing/index?'.$url_param);
                    }
                }
            }
        }

        if ($is_can)
        {
            include_once APP_VIEWS.'testing/edit.php';
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
        $page = 1;
        $errors = false;
        $date_time = new DateTime();
        $testing = [];
        $tid = null;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_DIRECTION)
            {
                $is_can = true;
                break;
            }
        }

        if (isset($_GET['s_direction']))
        {
            $search['direction_id'] = htmlspecialchars($_GET['s_direction']);
        }

        if (isset($_GET['s_test']))
        {
            $search['test_id'] = htmlspecialchars($_GET['s_test']);
        }

        if (isset($_GET['s_name']))
        {
            $search['name'] = htmlspecialchars($_GET['s_name']);
        }

        if (isset($_GET['page']))
        {
            $page = intval(htmlspecialchars($_GET['page']));
            if ($page < 1)
            {
                $page = 1;
            }
        }

        if (isset($_GET['tid']))
        {
            $tid = htmlspecialchars($_GET['tid']);
        }

        $url_param .= 's_direction='.$search['direction_id'].'&s_test='.$search['test_id']
            .'&s_name='.$search['name'];

        $testing = Testing::getTesting($tid);

        if ($testing['flag'] == FLAG_NO_CHANGE)
        {
            $errors['no_change'] = 'Невозможно изменить данное тестирование';
        }

        if (isset($_POST['yes']))
        {
            if ($tid != $testing['id'])
            {
                $errors['id'] = 'Невозможно внести изменения для данного тестирования';
            }
            if ($errors === false)
            {
                $testing['change_user_id'] = User::checkLogged();
                $testing['change_datetime'] = $date_time->format('Y-m-d H:i:s');
                Testing::delete($testing);
                $total = Testing::getTotalTestingList($search);
                if ($total <= Testing::SHOW_BY_DEFAULT)
                {
                    $page = 1;
                }
                $url_param .= '&page='.$page;
                header('Location: /testing/index?'.$url_param);
            }
        }
        $url_param .= '&page='.$page;
        if (isset($_POST['no']))
        {
            header('Location: /testing/index?'.$url_param);
        }


        $url_param .= '&page='.$page;

        if ($is_can)
        {
            include_once APP_VIEWS.'testing/delete.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }
}