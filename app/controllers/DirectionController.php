<?php


class DirectionController extends BaseController
{
    public function index()
    {
        $user_right = parent::getUserRight();
        $is_can = false;
        $search = [];
        $page = 1;
        $index_number = 1;
        $directions = [];
        $total_direction = 0;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_DIRECTION)
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
        $html_element['name']->setCaption('Направление');
        $html_element['name']->setConfig('type', 'text');
        $html_element['name']->setConfig('class', 'uk-width-1-1');
        $html_element['name']->setConfig('placeholder', 'Направление');
        $html_element['name']->setValueFromRequest();

        if ($html_element['name']->getValue() != null)
        {
            $search['name'] = trim($html_element['name']->getValue());
        }

        if ($is_can)
        {
            $directions = Direction::getDirections($search, $page);
            $total_direction = Direction::getTotalDirections($search);
            $index_number = ($page - 1) * Direction::SHOW_BY_DEFAULT;
            $pagination = new Pagination($total_direction, $page, Direction::SHOW_BY_DEFAULT, 'page=');

            include_once APP_VIEWS.'direction/index.php';
        }
        else
        {
            header('Location: /main/error');
        }


    }
}