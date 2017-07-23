<?php


class DirectionController extends BaseController
{
    public function index()
    {
        $user_right = parent::getUserRight();
        $is_can = false;
        $search = [];
        $page = 1;
        $directions = [];
        $total_direction = 0;


        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == 'CAN_MODERATOR_DIRECTION')
            {
                $is_can = true;
                break;
            }
        }

        if ($is_can)
        {
            $page = 2;
            $search['name'] = 'нап';
            $directions = Direction::getDirections($search, $page);
            $total_direction = Direction::getTotalDirections($search);

            include_once APP_VIEWS.'direction/index.php';
        }
        else
        {
            header('Location: /main/error');
        }


    }
}