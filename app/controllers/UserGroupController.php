<?php


class UserGroupController extends BaseController
{
    public function actionIndex()
    {
        $user_right = parent::getUserRight();
        $app_state = new App_State();
        $url_param = '';
        $is_can = false;
        $search = [];
        $page = 1;
        $index_number = 0;
        $user_groups = [];
        $total = 0;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_USER_GROUP)
            {
                $is_can = true;
                break;
            }
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }

        if ($page < 1)
        {
            $page = 1;
        }

        $html_element['name'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['name']->setName('s_name');
        $html_element['name']->setId('s_name');
        $html_element['name']->setCaption('Группа');
        $html_element['name']->setConfig('type', 'text');
        $html_element['name']->setConfig('class', 'uk-width-1-1');
        $html_element['name']->setConfig('placeholder', 'Группа');
        $html_element['name']->setValueFromRequest();

        if ($html_element['name']->getValue() != null)
        {
            $search['name'] = trim($html_element['name']->getValue());
        }

        if ($is_can)
        {
            $user_groups = User_Group::getUserGroups($search, $page);
            $total = User_Group::getTotalUserGroups($search);
            $index_number = User_Group::getIndexNumber($page);
            $pagination = new Pagination($total, $page, User_Group::SHOW_BY_DEFAULT, 'page=');

            $url_param .= 's_name='.$search['name'].'&page='.$page;

            include_once APP_VIEWS.'user_group/index.php';
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
        $user_group = [];

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_USER_GROUP)
            {
                $is_can = true;
                break;
            }
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

        $url_param .= 's_name='.$search['name'].'&page='.$page;

        $html_element['name'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['name']->setName('name');
        $html_element['name']->setId('name');
        $html_element['name']->setMin(1);
        $html_element['name']->setMax(110);
        $html_element['name']->setCaption('Наименование');
        $html_element['name']->setConfig('type', 'text');
        $html_element['name']->setConfig('class', 'uk-width-1-1');
        $html_element['name']->setConfig('placeholder', 'Группа пользователей');
        $html_element['name']->setValueFromRequest();

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

            $html_element['name']->check();

            if (!$html_element['name']->getCheck())
            {
                $errors['name'] = 'Ошибка в поле "'.$html_element['name']->getCaption().'".';
            }

            if ($errors === false)
            {
                $user_group['name'] = $html_element['name']->getValue();
                $user_group['change_user_id'] = User::checkLogged();
                $user_group['change_datetime'] = $date_time->format('Y-m-d H:i:s');
                $user_group['flag'] = $option_flag_select;
                $is_add = User_Group::add($user_group);
                if ($is_add !== false)
                {
                    header('Location: /user_group/index?'.$url_param);
                }
                else
                {
                    $errors['add'] = 'Ничего не удалось добавить! Возможно у вас нет прав';
                }
            }
        }

        if ($is_can)
        {
            include_once APP_VIEWS.'user_group/add.php';
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
        $user_group = [];
        $ugid = null;

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
        if (isset($_GET['page']))
        {
            $page = intval(htmlspecialchars($_GET['page']));
            if ($page < 1)
            {
                $page = 1;
            }
        }
        if (isset($_GET['ugid']))
        {
            $ugid = htmlspecialchars($_GET['ugid']);
        }

        $url_param .= 's_name='.$search['name'].'&page='.$page;

        $user_group = User_Group::getUserGroup($ugid);

        $html_element['name'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['name']->setName('name');
        $html_element['name']->setId('name');
        $html_element['name']->setValue($user_group['name']);
        $html_element['name']->setMin(1);
        $html_element['name']->setMax(110);
        $html_element['name']->setCaption('Наименование');
        $html_element['name']->setConfig('type', 'text');
        $html_element['name']->setConfig('class', 'uk-width-1-1');
        $html_element['name']->setConfig('placeholder', 'Группа пользователей');
        $html_element['name']->setValueFromRequest();

        $option_flag_select = $user_group['flag'];
        $option_flag = [];
        $optgroup_flag = [];

        if (isset($_POST['flag']))
        {
            $option_flag_select = $_POST['flag'];
            $option_flag_select = intval($option_flag_select);
        }
        if ($option_flag_select != FLAG_OFF
            && $option_flag_select != FLAG_ON)
        {
            $option_flag_select = FLAG_OFF;
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

        if ($user_group['flag'] == FLAG_NO_CHANGE)
        {
            $errors['no_change'] = 'Невозможно изменить данную группу пользователей';
            $html_element['name']->setConfig('disabled', 'disabled');
            $html_element['flag']->setConfig('disabled', 'disabled');
            $option_flag_select = FLAG_NO_CHANGE;
        }


        if (isset($_POST['edit']))
        {
            $html_element['name']->setValue(trim($html_element['name']->getValue()));

            $html_element['name']->check();

            if (!$html_element['name']->getCheck())
            {
                $errors['name'] = 'Ошибка в поле "'.$html_element['name']->getCaption().'".';
            }

            if ($ugid != $user_group['id'])
            {
                $errors['id'] = 'Невозможно внести изменения для данной группы пользователей';
            }

            if ($errors === false)
            {
                $user_group['name'] = $html_element['name']->getValue();
                $user_group['change_user_id'] = User::checkLogged();
                $user_group['change_datetime'] = $date_time->format('Y-m-d H:i:s');
                $user_group['flag'] = $option_flag_select;
                if ($user_group['flag'] != FLAG_NO_CHANGE)
                {
                    User_Group::edit($user_group);
                    header('Location: /user_group/index?'.$url_param);
                }
            }
        }

        if ($is_can)
        {
            include_once APP_VIEWS.'user_group/edit.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }
}