<?php


class TestController extends BaseController
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

        $html_element['name'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['name']->setName('s_name');
        $html_element['name']->setId('s_name');
        $html_element['name']->setCaption('Тест');
        $html_element['name']->setConfig('type', 'text');
        $html_element['name']->setConfig('class', 'uk-width-1-1');
        $html_element['name']->setConfig('placeholder', 'Тест');
        $html_element['name']->setValueFromRequest();



        if ($option_direction_selected > 0)
        {
            $search['direction_id'] = $option_direction_selected;

            if ($html_element['name']->getValue() != null)
            {
                $search['name'] = trim($html_element['name']->getValue());
            }

            $tests = Test::getTests($search, $page);
            $total = Test::getTotalTests($search);
            $index_number = Test::getIndexNumber($page);
            $pagination = new Pagination($total, $page, Test::SHOW_BY_DEFAULT, 'page=');
        }

        if ($is_can)
        {
            $url_param .= 's_direction='.$search['direction_id'].'&s_name='.$search['name'].'&page='.$page;

            include_once APP_VIEWS.'test/index.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }

    public function actionAdd()
    {
        $user_right = parent::getUserRight();
        $url_param = '';
        $is_can = false;
        $search = [];
        $page = 1;
        $errors = false;
        $date_time = new DateTime();
        $directions = [];
        $option_direction_selected = null;
        $test = [];


        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_DIRECTION)
            {
                $is_can = true;
                break;
            }
        }

        if (isset($_GET['s_name']))
        {
            $search['name'] = htmlspecialchars($_GET['s_name']);
        }

        if (isset($_GET['s_direction']))
        {
            $search['direction_id'] = htmlspecialchars($_GET['s_direction']);
            $option_direction_selected = $search['direction_id'];
        }

        if (isset($_GET['page']))
        {
            $page = intval(htmlspecialchars($_GET['page']));
            if ($page < 1)
            {
                $page = 1;
            }
        }

        $url_param .= 's_direction='.$search['direction_id'].'&s_name='.$search['name'].'&page='.$page;

        if (isset($_POST['direction_id']))
        {
            $test['direction_id'] = htmlspecialchars($_POST['direction_id']);
            $option_direction_selected = $test['direction_id'];
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
        $html_element['direction']->setName('direction_id');
        $html_element['direction']->setId('direction_id');
        $html_element['direction']->setNecessarily(true);
        $html_element['direction']->setConfig('data-placeholder', 'Не выбрано');
        $html_element['direction']->setConfig('class', 'uk-width-1-1');

        $html_element['name'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['name']->setName('name');
        $html_element['name']->setId('name');
        $html_element['name']->setMin(1);
        $html_element['name']->setMax(500);
        $html_element['name']->setCaption('Наименование');
        $html_element['name']->setConfig('type', 'text');
        $html_element['name']->setConfig('class', 'uk-width-1-1');
        $html_element['name']->setConfig('placeholder', 'Тест');
        $html_element['name']->setValueFromRequest();

        $html_element['comment'] = new \HTMLElement\HTMLTextTextareaElement();
        $html_element['comment']->setName('comment');
        $html_element['comment']->setId('comment');
        $html_element['comment']->setMax(1000);
        $html_element['comment']->setCaption('Комментарий');
        $html_element['comment']->setConfig('rows', '7');
        $html_element['comment']->setConfig('class', 'uk-width-1-1');
        $html_element['comment']->setConfig('placeholder', 'Комментарий');
        $html_element['comment']->setValueFromRequest();

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
            $html_element['name']->setValue(trim($html_element['name']->getValue()));
            $html_element['comment']->setValue(trim($html_element['comment']->getValue()));

            $html_element['name']->check();
            $html_element['comment']->check();

            $temp_diretion = false;
            foreach ($directions as $value)
            {
                if ($option_direction_selected == $value['id'])
                {
                    if ($option_direction_selected != 0)
                    {
                        $temp_diretion = true;
                        break;
                    }
                }
            }

            if (!$temp_diretion)
            {
                $errors['direction'] = 'Ошибка в поле "'.$html_element['direction']->getCaption().'".<br />Не выбрано направление.';
            }

            if (!$html_element['name']->getCheck())
            {
                $errors['name'] = 'Ошибка в поле "'.$html_element['name']->getCaption().'".';
            }

            if (!$html_element['comment']->getCheck())
            {
                $errors['comment'] = 'Ошибка в поле "'.$html_element['comment']->getCaption().'".';
            }

            if ($errors === false)
            {
                $test['name'] = $html_element['name']->getValue();
                $test['comment'] = $html_element['comment']->getValue();
                $test['direction_id'] = $option_direction_selected;
                $test['change_user_id'] = User::checkLogged();
                $test['change_datetime'] = $date_time->format('Y-m-d H:i:s');
                $test['flag'] = $option_flag_select;
                $is_add = Test::add($test);
                if ($is_add !== false)
                {
                    header('Location: /test/index?'.$url_param);
                }
                else
                {
                    $errors['add'] = 'Ничего не удалось добавить! Возможно у вас нет прав';
                }
            }
        }

        if ($is_can)
        {
            include_once APP_VIEWS.'test/add.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }

    public function actionEdit()
    {
        $user_right = parent::getUserRight();
        $url_param = '';
        $is_can = false;
        $search = [];
        $page = 1;
        $errors = false;
        $date_time = new DateTime();
        $directions = [];
        $option_direction_selected = null;
        $test = [];
        $tid = null;


        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_DIRECTION)
            {
                $is_can = true;
                break;
            }
        }

        if (isset($_GET['s_name']))
        {
            $search['name'] = htmlspecialchars($_GET['s_name']);
        }

        if (isset($_GET['s_direction']))
        {
            $search['direction_id'] = htmlspecialchars($_GET['s_direction']);
            $option_direction_selected = $search['direction_id'];
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

        $url_param .= 's_direction='.$search['direction_id'].'&s_name='.$search['name'].'&page='.$page;

        $test = Test::getTest($tid);

        $option_direction_selected = $test['direction_id'];

        if (isset($_POST['direction_id']))
        {
            $test['direction_id'] = htmlspecialchars($_POST['direction_id']);
            $option_direction_selected = $test['direction_id'];
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
        $html_element['direction']->setName('direction_id');
        $html_element['direction']->setId('direction_id');
        $html_element['direction']->setNecessarily(true);
        $html_element['direction']->setConfig('data-placeholder', 'Не выбрано');
        $html_element['direction']->setConfig('class', 'uk-width-1-1');

        $html_element['name'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['name']->setName('name');
        $html_element['name']->setId('name');
        $html_element['name']->setValue($test['name']);
        $html_element['name']->setMin(1);
        $html_element['name']->setMax(500);
        $html_element['name']->setCaption('Наименование');
        $html_element['name']->setConfig('type', 'text');
        $html_element['name']->setConfig('class', 'uk-width-1-1');
        $html_element['name']->setConfig('placeholder', 'Тест');
        $html_element['name']->setValueFromRequest();

        $html_element['comment'] = new \HTMLElement\HTMLTextTextareaElement();
        $html_element['comment']->setName('comment');
        $html_element['comment']->setId('comment');
        $html_element['comment']->setValue($test['comment']);
        $html_element['comment']->setMax(1000);
        $html_element['comment']->setCaption('Комментарий');
        $html_element['comment']->setConfig('rows', '7');
        $html_element['comment']->setConfig('class', 'uk-width-1-1');
        $html_element['comment']->setConfig('placeholder', 'Комментарий');
        $html_element['comment']->setValueFromRequest();

        $option_flag_select = $test['flag'];
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

        if ($test['flag'] == FLAG_NO_CHANGE)
        {
            $errors['no_change'] = 'Невозможно изменить данное направление';
            $html_element['direction']->setConfig('disabled', 'disabled');
            $html_element['name']->setConfig('disabled', 'disabled');
            $html_element['comment']->setConfig('disabled', 'disabled');
            $html_element['flag']->setConfig('disabled', 'disabled');
            $option_flag_select = FLAG_NO_CHANGE;
        }

        if (isset($_POST['edit']))
        {
            $html_element['name']->setValue(trim($html_element['name']->getValue()));
            $html_element['comment']->setValue(trim($html_element['comment']->getValue()));

            $html_element['name']->check();
            $html_element['comment']->check();

            $temp_diretion = false;
            foreach ($directions as $value)
            {
                if ($option_direction_selected == $value['id'])
                {
                    if ($option_direction_selected != 0)
                    {
                        $temp_diretion = true;
                        break;
                    }
                }
            }

            if (!$temp_diretion)
            {
                $errors['direction'] = 'Ошибка в поле "'.$html_element['direction']->getCaption().'".<br />Не выбрано направление.';
            }

            if (!$html_element['name']->getCheck())
            {
                $errors['name'] = 'Ошибка в поле "'.$html_element['name']->getCaption().'".';
            }

            if (!$html_element['comment']->getCheck())
            {
                $errors['comment'] = 'Ошибка в поле "'.$html_element['comment']->getCaption().'".';
            }

            if ($tid != $test['id'])
            {
                $errors['id'] = 'Невозможно внести изменения для данного теста';
            }

            if ($errors === false)
            {
                $test['name'] = $html_element['name']->getValue();
                $test['comment'] = $html_element['comment']->getValue();
                $test['direction_id'] = $option_direction_selected;
                $test['change_user_id'] = User::checkLogged();
                $test['change_datetime'] = $date_time->format('Y-m-d H:i:s');
                $test['flag'] = $option_flag_select;
                if ($test['flag'] != FLAG_NO_CHANGE)
                {
                    Test::edit($test);
                    header('Location: /test/index?'.$url_param);
                }
            }
        }

        if ($is_can)
        {
            include_once APP_VIEWS.'test/edit.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }
}