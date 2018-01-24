<?php

class UserOrAppRightController extends BaseController
{
    public function actionIndex()
    {
        $user_right = parent::getUserRight();
        $app_state = new App_State();
        $url_param = '';
        $is_can = false;
        $u_id = User::checkLogged();
        $search = [];
        $page = 1;
        $index_number = 1;
        $users = [];
        $total = 0;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_ADMINISTRATOR_USER_OR_APP_RIGHT)
            {
                $is_can = true;
                break;
            }
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }

        if (isset($_GET['p_page']))
        {
            $page = htmlspecialchars($_GET['p_page']);
        }

        if ($page < 1)
        {
            $page = 1;
        }

        $html_element['name'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['name']->setName('s_name');
        $html_element['name']->setId('s_name');
        $html_element['name']->setCaption('Пользователь');
        $html_element['name']->setConfig('type', 'text');
        $html_element['name']->setConfig('class', 'uk-width-1-1');
        $html_element['name']->setConfig('placeholder', 'ФИО или логин');
        $html_element['name']->setValueFromRequest();

        if ($html_element['name']->getValue() != null)
        {
            $search['name'] = trim($html_element['name']->getValue());
        }

        if ($is_can)
        {
            $users = User::getUsers($search, $page);
            $total = User::getTotalUsers($search);
            $index_number = User::getIndexNumber($page);
            $pagination = new Pagination($total, $page, User::SHOW_BY_DEFAULT, 'page=');

            $url_param .= 's_name='.$search['name'].'&page='.$page;

            include_once APP_VIEWS.'user_or_app_right/index.php';
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
        $user = [];
        $uid = null;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_ADMINISTRATOR_USER_OR_APP_RIGHT)
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
        if (isset($_GET['uid']))
        {
            $uid = htmlspecialchars($_GET['uid']);
        }

        $url_param .= 's_name='.$search['name'];

        $user = User::getUser($uid);

        if ($user['flag'] == FLAG_NO_CHANGE)
        {
            $errors['no_change'] = 'Невозможно изменить данного пользователя';
        }

        if (isset($_POST['yes']))
        {
            if ($uid != $user['id'])
            {
                $errors['id'] = 'Невозможно внести изменения для данного пользователя';
            }
            if ($errors === false)
            {
                $user['change_user_id'] = User::checkLogged();
                $user['change_datetime'] = $date_time->format('Y-m-d H:i:s');
                User::delete($user);
                $total = User::getTotalUsers($search);
                if ($total <= User::SHOW_BY_DEFAULT)
                {
                    $page = 1;
                }
                $url_param .= '&page='.$page;
                header('Location: /user_or_app_right/index?'.$url_param);
            }
        }
        $url_param .= '&page='.$page;
        if (isset($_POST['no']))
        {
            header('Location: /user_or_app_right/index?'.$url_param);
        }

        if ($is_can)
        {
            include_once APP_VIEWS.'user_or_app_right/delete.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }
}