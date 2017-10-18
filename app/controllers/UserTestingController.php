<?php


class UserTestingController extends BaseController
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
        $directions = [];
        $option_direction_selected = null;
        $testing_list = [];
        $option_testing_list_selected = null;
        $user_groups = [];
        $option_user_groups_selected = null;
        $users = [];
        $testing_user_list = [];
        $changes = false;

        $total = 0;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_USER_TESTING)
            {
                $is_can = true;
                break;
            }
        }

        if (isset($_GET['s_direction']))
        {
            $option_direction_selected = htmlspecialchars($_GET['s_direction']);
            $search['direction_id'] = htmlspecialchars($_GET['s_direction']);
        }

        if (isset($_GET['s_testing']))
        {
            $option_testing_list_selected = htmlspecialchars($_GET['s_testing']);
            $search['testing_id'] = htmlspecialchars($_GET['s_testing']);
        }

        if (isset($_GET['s_user_group']))
        {
            $option_user_groups_selected = htmlspecialchars($_GET['s_user_group']);
            $search['user_group_id'] = htmlspecialchars($_GET['s_user_group']);
        }

        $directions = Direction::getDirectionsByState(STATE_ON);
        $option_direction = [];
        $optgroup_direction = [];

        $i = 0;
        $option_direction[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_direction[$i]->setValue(0);
        $option_direction[$i]->setText('[выбрать]');

        $i = 1;

        foreach ($directions as $value)
        {
            $option_direction[$i] = new \HTMLElement\HTMLSelectOptionElement();
            $option_direction[$i]->setValue($value['id']);
            $option_direction[$i]->setText($value['name']);

            if ($option_direction_selected == $option_direction[$i]->getValue())
            {
                $option_direction[$i]->setSelected(true);
            }

            $i++;

        }

        $html_element['direction'] = new \HTMLElement\HTMLSelectElement();
        $html_element['direction']->setCaption('Направление');
        $html_element['direction']->setName('s_direction');
        $html_element['direction']->setId('s_direction');
        $html_element['direction']->setConfig('data-placeholder', 'Не выбрано');
        $html_element['direction']->setConfig('onchange', 'this.form.submit();');
        $html_element['direction']->setConfig('class', 'uk-width-1-1');

        $testing_list = Testing::getTestingListByDirection($search);
        $option_testing_list = [];
        $optgroup_testing_list = [];

        $i = 0;
        $option_testing_list[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_testing_list[$i]->setValue(0);
        $option_testing_list[$i]->setText('[выбрать]');

        $i = 1;
        foreach ($testing_list as $value)
        {
            $option_testing_list[$i] = new \HTMLElement\HTMLSelectOptionElement();
            $option_testing_list[$i]->setValue($value['id']);
            $option_testing_list[$i]->setText($value['name']);

            if ($option_testing_list_selected == $option_testing_list[$i]->getValue())
            {
                $option_testing_list[$i]->setSelected(true);
            }

            $i++;
        }

        $html_element['testing'] = new \HTMLElement\HTMLSelectElement();
        $html_element['testing']->setCaption('Тестирование');
        $html_element['testing']->setName('s_testing');
        $html_element['testing']->setId('s_testing');
        $html_element['testing']->setConfig('data-placeholder', 'Не выбрано');
        $html_element['testing']->setConfig('onchange', 'this.form.submit();');
        $html_element['testing']->setConfig('class', 'uk-width-1-1');

        $search['state'] = STATE_ON;
        $user_groups = User_Group::getUserGroupsForUser(array(), $search);
        $option_user_groups = [];
        $optgroup_user_groups = [];

        $i = 0;
        $option_user_groups[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_user_groups[$i]->setValue(0);
        $option_user_groups[$i]->setText('[выбрать]');

        $i = 1;
        foreach ($user_groups as $value)
        {
            $option_user_groups[$i] = new \HTMLElement\HTMLSelectOptionElement();
            $option_user_groups[$i]->setValue($value['id']);
            $option_user_groups[$i]->setText($value['name']);

            if ($option_user_groups_selected == $option_user_groups[$i]->getValue())
            {
                $option_user_groups[$i]->setSelected(true);
            }

            $i++;
        }

        $html_element['user_groups'] = new \HTMLElement\HTMLSelectElement();
        $html_element['user_groups']->setCaption('Группа');
        $html_element['user_groups']->setName('s_user_group');
        $html_element['user_groups']->setId('s_user_group');
        $html_element['user_groups']->setConfig('data-placeholder', 'Не выбрано');
        $html_element['user_groups']->setConfig('onchange', 'this.form.submit();');
        $html_element['user_groups']->setConfig('class', 'uk-width-1-1');

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
            $testing_user_list = User_Testing::getUsersByTestingByGroup($search);
            $users = User_User_Group::getUsersByGroupIDBySearch($search);
            $total = count($users);

            $url_param .= 's_direction='.$search['direction_id'].'&s_testing='. $search['testing_id']
                .'&s_user_group='.$search['user_group_id'].'&s_name='.$search['name'];

            if (isset($_POST['save']))
            {
                if (is_array($testing_user_list))
                {
                    if (count($testing_user_list) >= 1)
                    {
                        $filtered_tul = [];
                        foreach ($testing_user_list as $tul_item)
                        {
                            $filtered_tul[] = $tul_item['id'];
                        }
                        User_Testing::deleteSelected($filtered_tul);

                    }
                    $user_list_to_test = null;
                    if (isset($_POST['uid']))
                    {
                        $user_list_to_test = $_POST['uid'];
                    }
                    if (is_array($user_list_to_test) && count($user_list_to_test) > 0)
                    {
                        User_Testing::addTestsForUsers($user_list_to_test, $search);
                    }
                }
                $testing_user_list = User_Testing::getUsersByTestingByGroup($search);
                $changes = true;
            }

            include_once APP_VIEWS.'user_testing/index.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }
}