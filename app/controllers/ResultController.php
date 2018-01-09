<?php

class ResultController extends BaseController
{
    public function actionIndex()
    {
        $user_right = parent::getUserRight();
        $u_id = User::checkLogged();
        $search = [];
        $page = 1;
        $errors = false;

        $user_id_to_view = 0;

        $is_can = false;
        $is_can_other_result_view = false;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_OTHER_RESULT_VIEW)
            {
                $is_can_other_result_view = true;
            }
            if ($u_r['right_name'] == CAN_RESULT_VIEW)
            {
                $is_can = true;
            }
            if ($is_can === true && $is_can_other_result_view === true)
            {
                break;
            }
        }

        if (isset($_GET['testing_result_id']))
        {
            $search['testing_result_id'] = intval(htmlspecialchars($_GET['testing_result_id']));
        }

        if (isset($_GET['user_id']))
        {
            $search['user_id'] = intval(htmlspecialchars($_GET['user_id']));
        }

        if (isset($_GET['page']))
        {
            $page = intval(htmlspecialchars($_GET['page']));
            if ($page < 1)
            {
                $page = 1;
            }
        }



        if ($is_can)
        {
            include_once APP_VIEWS.'result/index.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }

    public function actionView()
    {
        $user_right = parent::getUserRight();
        $u_id = User::checkLogged();
        $search = [];
        $page = 1;
        $errors = false;

        $testing_result_info = []; // Массив с данными отчета результата

        $user_id_to_view = 0;

        $is_can = false;
        $is_can_other_result_view = false;

        $count_wrong = 0;
        $count_scip = 0;
        $count_correct = 0;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_OTHER_RESULT_VIEW)
            {
                $is_can_other_result_view = true;
            }
            if ($u_r['right_name'] == CAN_RESULT_VIEW)
            {
                $is_can = true;
            }
            if ($is_can === true && $is_can_other_result_view === true)
            {
                break;
            }
        }

        if (isset($_GET['testing_result_id']))
        {
            $search['testing_result_id'] = intval(htmlspecialchars($_GET['testing_result_id']));
        }

        if (isset($_GET['user_id']))
        {
            $search['user_id'] = intval(htmlspecialchars($_GET['user_id']));
        }

        if (isset($_GET['page']))
        {
            $page = intval(htmlspecialchars($_GET['page']));
            if ($page < 1)
            {
                $page = 1;
            }
        }

        if ($is_can_other_result_view && $search['user_id'] > 0)
        {
            $user_id_to_view = $search['user_id'];
        }
        else
        {
            $user_id_to_view = $u_id;
        }

        $testing_result_info = Testing_Result::getTestingResult($search['testing_result_id'], $user_id_to_view);
        if (!is_array($testing_result_info) && count($testing_result_info) < 1)
        {
            $errors['no_testing_result'] = 'Результат не найден';
            goto _gt_view;
        }





        _gt_view:
        if ($is_can)
        {
            include_once APP_VIEWS.'result/view.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }
}