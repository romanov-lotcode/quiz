<?php

class UserController extends BaseController
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
        $users = [];
        $total = 0;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_USER)
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

            include_once APP_VIEWS.'user/index.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }
}