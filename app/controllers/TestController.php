<?php


class TestController extends BaseController
{
    public function index()
    {
        $user_right = parent::getUserRight();
        $app_state = new App_State();
        $url_param = '';
        $is_can = false;
        $search = [];
        $page = 1;
        $index_number = 1;
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

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }

        if ($page < 1)
        {
            $page = 1;
        }



        if ($is_can)
        {


            $url_param .= 's_name='.$search['name'].'&page='.$page;

            include_once APP_VIEWS.'test/index.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }


}