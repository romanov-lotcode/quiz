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
}