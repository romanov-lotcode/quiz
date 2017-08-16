<?php

class TestingController extends BaseController
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
        $directions = [];
        $option_direction_selected = null;
        $tests = [];
        $option_test_selected = null;
        $testing_list = [];
        $total = 0;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_TEST)
            {
                $is_can = true;
                break;
            }
        }

        if (isset($_GET['s_direction']))
        {
            $option_direction_selected = htmlspecialchars($_GET['s_direction']);

        }

        if (isset($_GET['s_test']))
        {
            $option_test_selected = htmlspecialchars($_GET['s_test']);
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }

        if ($page < 1)
        {
            $page = 1;
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

        $tests = Test::getTestsByDirectionAndState($option_direction_selected, STATE_ON);
        $option_test = [];
        $optgroup_test = [];

        $i = 0;
        $option_test[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_test[$i]->setValue(0);
        $option_test[$i]->setText('[выбрать]');

        $i = 1;

        foreach ($tests as $value)
        {
            $option_test[$i] = new \HTMLElement\HTMLSelectOptionElement();
            $option_test[$i]->setValue($value['id']);
            $option_test[$i]->setText($value['name']);

            if ($option_test_selected == $option_test[$i]->getValue())
            {
                $option_test[$i]->setSelected(true);
            }

            $i++;
        }

        $html_element['test'] = new \HTMLElement\HTMLSelectElement();
        $html_element['test']->setCaption('Тест');
        $html_element['test']->setName('s_test');
        $html_element['test']->setId('s_test');
        $html_element['test']->setConfig('data-placeholder', 'Не выбрано');
        $html_element['test']->setConfig('onchange', 'this.form.submit();');
        $html_element['test']->setConfig('class', 'uk-width-1-1');

        $html_element['name'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['name']->setName('s_name');
        $html_element['name']->setId('s_name');
        $html_element['name']->setCaption('Тестирование');
        $html_element['name']->setConfig('type', 'text');
        $html_element['name']->setConfig('class', 'uk-width-1-1');
        $html_element['name']->setConfig('placeholder', 'Тестирование');
        $html_element['name']->setValueFromRequest();



        if ($option_direction_selected > 0 && $option_test_selected > 0)
        {
            $search['direction_id'] = $option_direction_selected;
            $search['test_id'] = $option_test_selected;
            if ($html_element['name']->getValue() != null)
            {
                $search['name'] = trim($html_element['name']->getValue());
            }
            $testing_list = Testing::getTestingList($search, $page);
            $total = Testing::getTotalTestingList($search);
            $index_number = Testing::getIndexNumber($page);
            $pagination = new Pagination($total, $page, Testing::SHOW_BY_DEFAULT, 'page=');
        }

        if ($is_can)
        {
            $url_param .= 's_direction='.$search['direction_id'].'&s_test='.$search['test_id']
                .'&s_name='.$search['name'].'&page='.$page;

            include_once APP_VIEWS.'testing/index.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }
}