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

    public function actionEdit()
    {
        $user_right = parent::getUserRight();
        $url_param = '';
        $is_can = false;
        $search = [];
        $page = 1;
        $errors = false;
        $user = [];
        $uid = null;
        $app_rights = App_Right::getAppRights();
        $checked_elements_moderator = null;
        $checked_elements_administrator = null;
        $u_id = User::checkLogged(); // ID авторизованного пользователя

        $sort_array_add = [];
        $sort_array_delete = [];

        // Права по умолчанию
        $moderator_nec_id = 4;
        $moderator_nec_value = 8;
        $administrator_nec_id = 15;
        $administrator_nec_value = 16384;


        $moderator = [];
        $moderator['4'] = 'CAN_VIEW_CORRECT_ANSWER'; // Может просматривать правильные ответы
        $moderator['16'] = 'CAN_MODERATOR_TEST'; // Может работать с тестами
        $moderator['32'] = 'CAN_MODERATOR_DIRECTION'; // Может работать с направлениями
        $moderator['64'] = 'CAN_MODERATOR_TESTING'; // Может работать с тестированиями
        $moderator['128'] = 'CAN_MODERATOR_QUESTION'; // Может работать с вопросами
        $moderator['256'] = 'CAN_MODERATOR_ANSWER'; // Может работать с ответами
        $moderator['512'] = 'CAN_MODERATOR_USER_GROUP'; // Может работать с группами пользователей
        $moderator['1024'] = 'CAN_MODERATOR_USER'; // Может работать с пользователями
        $moderator['2048'] = 'CAN_MODERATOR_USER_TESTING'; // Может назначать прохождения тестирований
        $moderator['4096'] = 'CAN_OTHER_RESULT_VIEW'; // Может просматривать результат других пользователей
        $moderator['8192'] = 'CAN_MODERATOR_RESULT'; // Может изменять результаты

        $administrator = [];
        $administrator['32768'] = 'CAN_ADMINISTRATOR_USER_OR_APP_RIGHT'; // Может задавать права пользователям

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

        $user_right = User::getUserRights($uid);
        $user = User::getUser($uid);
        if ($user['flag'] == FLAG_NO_CHANGE && $user['id'] != $u_id)
        {
            $errors['user'] = 'Вы не сможете изменить права данного пользователя';
        }

        $moderator_right = false;
        if (isset($_POST['moderator']))
        {
            $checked_elements_moderator = $_POST['moderator'];
            $moderator_right = true;
        }

        $administrator_right = false;
        if (isset($_POST['administrator']))
        {
            $checked_elements_administrator = $_POST['administrator'];
            $administrator_right = true;
        }

        if (isset($_POST['edit']))
        {
            $sort_array_temp = [];
            if (!$moderator_right)
            {
                $sort_array_delete[$moderator_nec_id] = $moderator_nec_value;
            }
            if (!$administrator_right)
            {
                $sort_array_delete[$administrator_nec_id] = $administrator_nec_value;
            }

            foreach ($checked_elements_moderator as $chem_key => $chem_value)
            {
                foreach ($app_rights as $a_r)
                {
                    if ($chem_value == $a_r['right_value'])
                    {
                        $sort_array_temp[$a_r['id']] = $chem_value;
                        $sort_array_temp[$moderator_nec_id] = $moderator_nec_value;
                    }
                }
            }

            foreach ($checked_elements_administrator as $chea_key => $chea_value)
            {
                foreach ($app_rights as $a_r)
                {
                    if ($chea_key == $a_r['right_value'])
                    {
                        $sort_array_temp[$a_r['id']] = $chea_key;
                        $sort_array_temp[$administrator_nec_id] = $administrator_nec_value;
                    }
                }
            }

            foreach ($user_right as $u_r)
            {
                foreach ($moderator as $m_key => $m_value)
                {
                    if ($u_r['right_value'] == $m_key)
                    {
                        $sort_array_delete[$u_r['app_right_id']] = $u_r['right_value'];
                        $sort_array_delete[$moderator_nec_id] = $moderator_nec_value;
                    }
                }

                foreach ($administrator as $a_key => $a_value)
                {
                    if ($u_r['right_value'] == $a_key)
                    {
                        $sort_array_delete[$u_r['app_right_id']] = $u_r['right_value'];
                        $sort_array_delete[$administrator_nec_id] = $administrator_nec_value;
                    }
                }
            }
            $sort_array_add = $sort_array_temp;
            ksort($sort_array_add);
            ksort($sort_array_delete);

            if ($errors === false)
            {
                foreach ($sort_array_delete as $app_right_id => $sad_value)
                {
                    User_Or_App_Right::delete($uid, $app_right_id);
                }
                foreach ($sort_array_add as $app_right_id => $saa_value)
                {
                    User_Or_App_Right::add($uid, $app_right_id);
                }
            }
        }

        $url_param .= 's_name='.$search['name']. '&page='.$page;

        $user_right = User::getUserRights($uid);
        $user_rights_array = [];
        foreach ($user_right as $u_r)
        {
            $user_rights_array[] = $u_r['right_value'];
        }

        $i = 0;
        foreach ($moderator as $m_key => $m_value)
        {
            $temp = null;
            foreach ($app_rights as $a_r)
            {
                if ($a_r['right_value'] == $m_key)
                {
                    $temp = $a_r;
                    break;
                }
            }
            if ($temp != null)
            {
                $checkbox_element['moderator_'.$i] = new \HTMLElement\HTMLCheckboxAndRadioCheckboxElement();
                $checkbox_element['moderator_'.$i]->setName('moderator['. $temp['right_value'] .']');
                $checkbox_element['moderator_'.$i]->setCaption($temp['description']);
                $checkbox_element['moderator_'.$i]->setValue($temp['right_value']);
                $checkbox_element['moderator_'.$i]->setConfig('class', 'moderator');
                if (is_array($user_rights_array) && in_array($temp['right_value'], $user_rights_array))
                {
                    $checkbox_element['moderator_'.$i]->setChecked(true);
                }
                $i++;
            }
        }

        $i = 0;
        foreach ($administrator as $a_key => $a_value)
        {
            $temp = null;
            foreach ($app_rights as $a_r)
            {
                if ($a_r['right_value'] == $a_key)
                {
                    $temp = $a_r;
                    break;
                }
            }
            if ($temp != null)
            {
                $checkbox_element['administrator_'.$i] = new \HTMLElement\HTMLCheckboxAndRadioCheckboxElement();
                $checkbox_element['administrator_'.$i]->setName('administrator['. $temp['right_value'] .']');
                $checkbox_element['administrator_'.$i]->setCaption($temp['description']);
                $checkbox_element['administrator_'.$i]->setValue($temp['right_value']);
                $checkbox_element['administrator_'.$i]->setConfig('class', 'administrator');
                if (is_array($user_rights_array) && in_array($temp['right_value'], $user_rights_array))
                {
                    $checkbox_element['administrator_'.$i]->setChecked(true);
                }
                $i++;
            }
        }

        if ($is_can)
        {
            include_once APP_VIEWS.'user_or_app_right/edit.php';
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