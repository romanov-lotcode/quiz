<?php


class UserUserGroupController extends BaseController
{
    public function actionIndex()
    {
        $user_right = parent::getUserRight();
        $url_param = '';
        $is_can = false;
        $u_id = User::checkLogged();
        $search = [];
        $page = 1;
        $index_number = 0;
        $user = [];
        $groups = [];
        $total = 0;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_USER)
            {
                $is_can = true;
                break;
            }
        }

        if (isset($_GET['s_name']))
        {
            $search['s_name'] = htmlspecialchars($_GET['s_name']);
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }
        if ($page < 1)
        {
            $page = 1;
        }

        if (isset($_GET['uid']))
        {
            $search['uid'] = htmlspecialchars($_GET['uid']);
        }

        if ($is_can)
        {
            $user = User::getUser($search['uid']);
            $groups = User_User_Group::getUserUserGroups($search);
            $total = User_User_Group::getTotalUserUserGroups($search);
            $url_param .= 's_name='.$search['s_name'].'&page='.$page;

            include_once APP_VIEWS.'user_user_group/index.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }

    public function actionAdd()
    {
        $user_right = parent::getUserRight();
        $validate = new App_Validate();
        $url_param = '';
        $is_can = false;
        $search = [];
        $page = 1;
        $errors = false;
        $date_time = new DateTime();
        $group = [];
        $option_user_groups_selected = null;
        $user = [];

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_USER)
            {
                $is_can = true;
                break;
            }
        }

        if (isset($_GET['s_name']))
        {
            $search['s_name'] = htmlspecialchars($_GET['s_name']);
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }
        if ($page < 1)
        {
            $page = 1;
        }

        if (isset($_GET['uid']))
        {
            $search['uid'] = htmlspecialchars($_GET['uid']);
        }

        $user = User::getUser($search['uid']);

        if ($user['id'] == null || $user['flag'] < 0)
        {
            $errors['no_user'] = 'Не выбран пользователь';
        }

        if (isset($_POST['group_id']))
        {
            $option_user_groups_selected = htmlspecialchars($_POST['group_id']);
        }

        $url_param .= 's_name='.$search['s_name'].'&page='.$page
            . '&uid=' .$search['uid'];

        $user_groups = User_User_Group::getUserUserGroups($search);
        $groups = null;

        if (is_array($user_groups) && count($user_groups) > 0)
        {
            foreach ($user_groups as $u_g)
            {
                if ($u_g['flag'] >= 0)
                {
                    $groups[] = $u_g['user_group_id'];
                }
            }
        }
        $user_groups = User_Group::getUserGroupsForUser($groups);
        $option_user_groups = [];
        $optgroup_user_groups = [];

        $i = 0;
        $option_user_groups[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_user_groups[$i]->setValue(0);
        $option_user_groups[$i]->setText('[выбрать]');

        $i = 1;
        $groups_to_check = null;
        foreach ($user_groups as $value)
        {
            $option_user_groups[$i] = new \HTMLElement\HTMLSelectOptionElement();
            $option_user_groups[$i]->setValue($value['id']);
            $option_user_groups[$i]->setText($value['name']);

            if ($option_user_groups_selected == $option_user_groups[$i]->getValue())
            {
                $option_user_groups[$i]->setSelected(true);
            }
            $groups_to_check[] = $value['id'];

            $i++;
        }

        $html_element['group'] = new \HTMLElement\HTMLSelectElement();
        $html_element['group']->setCaption('Группа');
        $html_element['group']->setName('group_id');
        $html_element['group']->setId('group_id');
        $html_element['group']->setNecessarily(true);
        $html_element['group']->setConfig('data-placeholder', 'Не выбрано');
        $html_element['group']->setConfig('class', 'uk-width-1-1');

        if (isset($_POST['add']))
        {
            if (!in_array($option_user_groups_selected, $groups_to_check))
            {
                $errors['group'] = 'Необходимо выбрать группу';
            }

            if ($errors === false)
            {
                $group['user_id'] = $user['id'];
                $group['user_group_id'] = $option_user_groups_selected;
                $group['date_admission'] = $date_time->format('Y-m-d');
                $group['change_user_id'] = User::checkLogged();
                $group['change_datetime'] = $date_time->format('Y-m-d H:i:s');
                $group['flag'] = 1;
                $is_add = User_User_Group::add($group);
                if ($is_add !== false)
                {
                    header('Location: /user_user_group/index?'.$url_param);
                }
                else
                {
                    $errors['add'] = 'Ничего не удалось добавить! Возможно у вас нет прав';
                }
            }
        }
        if ($is_can)
        {
            include_once APP_VIEWS.'user_user_group/add.php';
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
        $user_group = [];
        $uugid = null;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_USER)
            {
                $is_can = true;
                break;
            }
        }

        if (isset($_GET['s_name']))
        {
            $search['s_name'] = htmlspecialchars($_GET['s_name']);
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }
        if ($page < 1)
        {
            $page = 1;
        }

        if (isset($_GET['uid']))
        {
            $search['uid'] = htmlspecialchars($_GET['uid']);
        }

        if (isset($_GET['uugid']))
        {
            $search['uugid'] = htmlspecialchars($_GET['uugid']);
        }

        $url_param .= 's_name='.$search['s_name'].'&page='.$page
            . '&uid=' .$search['uid'];

        $user_group = User_User_Group::getUserUserGroup($search['uugid']);

        if (isset($_POST['yes']))
        {
            if ($search['uugid'] != $user_group['id'])
            {
                $errors['id'] = 'Невозможно внести изменения для данной записи';
            }
            if ($errors === false)
            {
                $user_group['date_deduction'] = $date_time->format('Y-m-d');
                $user_group['change_user_id'] = User::checkLogged();
                $user_group['change_datetime'] = $date_time->format('Y-m-d H:i:s');
                User_User_Group::delete($user_group);
                header('Location: /user_user_group/index?'.$url_param);
            }
        }
        if (isset($_POST['no']))
        {
            header('Location: /user_user_group/index?'.$url_param);
        }

        if ($is_can)
        {
            include_once APP_VIEWS.'user_user_group/delete.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }
}