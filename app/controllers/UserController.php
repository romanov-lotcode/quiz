<?php

class UserController extends BaseController
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

            include_once APP_VIEWS.'user/index.php';
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
        $replace_chars = include (ROOT . '/config/replace_chars.php');
        $url_param = '';
        $is_can = false;
        $search = [];
        $page = 1;
        $errors = false;
        $date_time = new DateTime();
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

        $url_param .= 's_name='.$search['name'].'&page='.$page;

        $html_element['lastname'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['lastname']->setName('lastname');
        $html_element['lastname']->setId('lastname');
        $html_element['lastname']->setMin(1);
        $html_element['lastname']->setMax(128);
        $html_element['lastname']->setCaption('Фамилия');
        $html_element['lastname']->setConfig('type', 'text');
        $html_element['lastname']->setConfig('class', 'uk-width-1-1');
        $html_element['lastname']->setConfig('placeholder', 'Фамилия');
        $html_element['lastname']->setValueFromRequest();

        $html_element['firstname'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['firstname']->setName('firstname');
        $html_element['firstname']->setId('firstname');
        $html_element['firstname']->setMin(1);
        $html_element['firstname']->setMax(64);
        $html_element['firstname']->setCaption('Имя');
        $html_element['firstname']->setConfig('type', 'text');
        $html_element['firstname']->setConfig('class', 'uk-width-medium-1-1');
        $html_element['firstname']->setConfig('placeholder', 'Имя');
        $html_element['firstname']->setValueFromRequest();

        $html_element['middlename'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['middlename']->setName('middlename');
        $html_element['middlename']->setId('middlename');
        $html_element['middlename']->setMax(128);
        $html_element['middlename']->setCaption('Отчество');
        $html_element['middlename']->setConfig('type', 'text');
        $html_element['middlename']->setConfig('class', 'uk-width-1-1');
        $html_element['middlename']->setConfig('placeholder', 'Отчество');
        $html_element['middlename']->setValueFromRequest();

        $html_element['login'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['login']->setName('login');
        $html_element['login']->setId('login');
        $html_element['login']->setMin(6);
        $html_element['login']->setMax(32);
        $html_element['login']->setCaption('Логин');
        $html_element['login']->setConfig('type', 'text');
        $html_element['login']->setConfig('class', 'uk-width-1-1');
        $html_element['login']->setConfig('placeholder', 'Логин');
        $html_element['login']->setValueFromRequest();

        $html_element['password'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['password']->setName('password');
        $html_element['password']->setId('password');
        $html_element['password']->setMin(6);
        $html_element['password']->setMax(40);
        $html_element['password']->setCaption('Пароль');
        $html_element['password']->setConfig('type', 'password');
        $html_element['password']->setConfig('class', 'uk-width-1-1');
        $html_element['password']->setConfig('placeholder', 'Пароль');
        $html_element['password']->setValueFromRequest();

        $html_element['email'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['email']->setName('email');
        $html_element['email']->setId('email');
        $html_element['email']->setMax(128);
        $html_element['email']->setCaption('Email');
        $html_element['email']->setConfig('type', 'text');
        $html_element['email']->setConfig('class', 'uk-width-1-1');
        $html_element['email']->setConfig('placeholder', 'Email');
        $html_element['email']->setValueFromRequest();

        $option_flag_select = FLAG_OFF;
        $option_flag = [];
        $optgroup_flag = [];

        if (isset($_POST['flag']))
        {
            $option_flag_select = $_POST['flag'];
            $option_flag_select = intval($option_flag_select);
            if ($option_flag_select != FLAG_OFF
                && $option_flag_select != FLAG_ON)
            {
                $option_flag_select = FLAG_OFF;
            }
        }

        $i = 0;
        $option_flag[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_flag[$i]->setValue(FLAG_ON);
        $option_flag[$i]->setText('Активен');
        ($option_flag_select == $option_flag[$i]->getValue())? $option_flag[$i]->setSelected(true):'';

        $i = 1;
        $option_flag[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_flag[$i]->setValue(FLAG_OFF);
        $option_flag[$i]->setText('Неактивен');
        ($option_flag_select == $option_flag[$i]->getValue())? $option_flag[$i]->setSelected(true):'';

        $html_element['flag'] = new \HTMLElement\HTMLSelectElement();
        $html_element['flag']->setCaption('Состояние');
        $html_element['flag']->setConfig('class', 'uk-width-1-4');
        $html_element['flag']->setName('flag');
        $html_element['flag']->setId('flag');

        if (isset($_POST['add']))
        {
            $lnSegments = [];
            $lnSegments = explode("-", $html_element['lastname']->getValue());
            $toImplode = [];
            foreach($lnSegments as $segments)
            {
                $toImplode[] = trim($validate->my_ucwords($segments));
            }
            $lastname = implode("-", $toImplode);
            $lastname = str_ireplace($replace_chars, "", $lastname);
            $html_element['lastname']->setValue($lastname);
            $html_element['firstname']->setValue(trim($validate->my_ucwords($html_element['firstname']->getValue())));
            $html_element['middlename']->setValue(trim($validate->my_ucwords($html_element['middlename']->getValue())));
            $html_element['login']->setValue(trim($html_element['login']->getValue()));


            $html_element['lastname']->check();
            $html_element['firstname']->check();
            $html_element['middlename']->check();
            $html_element['login']->check();
            $html_element['password']->check();
            $html_element['email']->check();

            if (!$html_element['lastname']->getCheck())
            {
                $errors['lastname'] = 'Ошибка в поле "'.$html_element['lastname']->getCaption().'".';
            }
            if (!$html_element['firstname']->getCheck())
            {
                $errors['firstname'] = 'Ошибка в поле "'.$html_element['firstname']->getCaption().'".';
            }
            if (!$html_element['middlename']->getCheck())
            {
                $errors['middlename'] = 'Ошибка в поле "'.$html_element['middlename']->getCaption().'".';
            }
            if (!User::checkLogin($html_element['login']->getValue()))
            {
                $html_element['login']->setCheck(false);
                $errors['login_db'] = 'Пользователь с таким логином уже зарегистрирован';
            }
            if (!$validate->checkLogin($html_element['login']->getValue()))
            {
                $html_element['login']->setCheck(false);
            }
            if (!$html_element['login']->getCheck())
            {
                $errors['login'] = 'Ошибка в поле "'. $html_element['login']->getCaption() .'".<br />
                Необходимо заполнить от 6 до 32 символов.';
            }
            if (!$validate->checkPassword($html_element['password']->getValue()))
            {
                $html_element['password']->setCheck(false);
            }
            if (!$html_element['password']->getCheck())
            {
                $errors['password'] = 'Ошибка в поле "'. $html_element['password']->getCaption() .'".<br />
                Необходимо заполнить от 6 до 20 символов.';
            }
            if (!empty($html_element['email']->getValue()))
            {
                $html_element['email']->setValue(trim($html_element['email']->getValue()));
                if (!$validate->checkEmail($html_element['email']->getValue()))
                {
                    $html_element['email']->setCheck(false);
                }
                if (!$html_element['email']->getCheck())
                {
                    $errors['email'] = 'Ошибка в поле "'. $html_element['email']->getCaption() .'".';
                }
            }

            if ($errors === false)
            {
                $user['registered_datetime'] = $date_time->format('Y-m-d H:i:s');
                $user['lastname'] = $html_element['lastname']->getValue();
                $user['firstname'] = $html_element['firstname']->getValue();
                $user['middlename'] = $html_element['middlename']->getValue();
                $user['login'] = $html_element['login']->getValue();
                $user['password'] = md5($html_element['password']->getValue());
                $user['email'] = $html_element['email']->getValue();
                $user['flag'] = $option_flag_select;

                $new_user_id = User::add($user);
                if ($new_user_id !== false)
                {
                    User::setDefaultUserRight($new_user_id);
                    header('Location: /user/index?'.$url_param);
                }
                else
                {
                    $errors['no_registration'] = 'Не удалось добавить пользователя';
                }
            }
        }

        if ($is_can)
        {
            include_once APP_VIEWS.'user/add.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }

    public function actionEdit()
    {
        $user_right = parent::getUserRight();
        $validate = new App_Validate();
        $replace_chars = include (ROOT . '/config/replace_chars.php');
        $url_param = '';
        $is_can = false;
        $search = [];
        $page = 1;
        $errors = false;
        $date_time = new DateTime();
        $user = [];
        $uid = null;
        $u_id = User::checkLogged();

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

        $url_param .= 's_name='.$search['name'].'&page='.$page;

        $user = User::getUser($uid);

        $html_element['lastname'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['lastname']->setName('lastname');
        $html_element['lastname']->setId('lastname');
        $html_element['lastname']->setValue($user['this_lastname']);
        $html_element['lastname']->setMin(1);
        $html_element['lastname']->setMax(128);
        $html_element['lastname']->setCaption('Фамилия');
        $html_element['lastname']->setConfig('type', 'text');
        $html_element['lastname']->setConfig('class', 'uk-width-1-1');
        $html_element['lastname']->setConfig('placeholder', 'Фамилия');
        $html_element['lastname']->setValueFromRequest();

        $html_element['firstname'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['firstname']->setName('firstname');
        $html_element['firstname']->setId('firstname');
        $html_element['firstname']->setValue($user['this_firstname']);
        $html_element['firstname']->setMin(1);
        $html_element['firstname']->setMax(64);
        $html_element['firstname']->setCaption('Имя');
        $html_element['firstname']->setConfig('type', 'text');
        $html_element['firstname']->setConfig('class', 'uk-width-medium-1-1');
        $html_element['firstname']->setConfig('placeholder', 'Имя');
        $html_element['firstname']->setValueFromRequest();

        $html_element['middlename'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['middlename']->setName('middlename');
        $html_element['middlename']->setId('middlename');
        $html_element['middlename']->setValue($user['this_middlename']);
        $html_element['middlename']->setMax(128);
        $html_element['middlename']->setCaption('Отчество');
        $html_element['middlename']->setConfig('type', 'text');
        $html_element['middlename']->setConfig('class', 'uk-width-1-1');
        $html_element['middlename']->setConfig('placeholder', 'Отчество');
        $html_element['middlename']->setValueFromRequest();

        $html_element['login'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['login']->setName('login');
        $html_element['login']->setId('login');
        $html_element['login']->setValue($user['login']);
        $html_element['login']->setMin(6);
        $html_element['login']->setMax(32);
        $html_element['login']->setCaption('Логин');
        $html_element['login']->setConfig('type', 'text');
        $html_element['login']->setConfig('class', 'uk-width-1-1');
        $html_element['login']->setConfig('placeholder', 'Логин');
        $html_element['login']->setValueFromRequest();

        $html_element['email'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['email']->setName('email');
        $html_element['email']->setId('email');
        $html_element['email']->setValue($user['email']);
        $html_element['email']->setMax(128);
        $html_element['email']->setCaption('Email');
        $html_element['email']->setConfig('type', 'text');
        $html_element['email']->setConfig('class', 'uk-width-1-1');
        $html_element['email']->setConfig('placeholder', 'Email');
        $html_element['email']->setValueFromRequest();

        $option_flag_select = $user['flag'];
        $option_flag = [];
        $optgroup_flag = [];

        if (isset($_POST['flag']))
        {
            $option_flag_select = $_POST['flag'];
            $option_flag_select = intval($option_flag_select);
            if ($option_flag_select != FLAG_OFF
                && $option_flag_select != FLAG_ON)
            {
                $option_flag_select = FLAG_OFF;
            }
        }

        $i = 0;
        $option_flag[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_flag[$i]->setValue(FLAG_ON);
        $option_flag[$i]->setText('Активен');
        ($option_flag_select == $option_flag[$i]->getValue())? $option_flag[$i]->setSelected(true):'';

        $i = 1;
        $option_flag[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_flag[$i]->setValue(FLAG_OFF);
        $option_flag[$i]->setText('Неактивен');
        ($option_flag_select == $option_flag[$i]->getValue())? $option_flag[$i]->setSelected(true):'';

        $html_element['flag'] = new \HTMLElement\HTMLSelectElement();
        $html_element['flag']->setCaption('Состояние');
        $html_element['flag']->setConfig('class', 'uk-width-1-4');
        $html_element['flag']->setName('flag');
        $html_element['flag']->setId('flag');

        if ($user['flag'] == FLAG_NO_CHANGE && $user['id'] != $u_id)
        {
            $errors['no_change'] = 'Невозможно изменить данного пользователя';
            $html_element['lastname']->setConfig('disabled', 'disabled');
            $html_element['firstname']->setConfig('disabled', 'disabled');
            $html_element['middlename']->setConfig('disabled', 'disabled');
            $html_element['login']->setConfig('disabled', 'disabled');
            $html_element['email']->setConfig('disabled', 'disabled');
            $html_element['flag']->setConfig('disabled', 'disabled');
            $option_flag_select = FLAG_NO_CHANGE;
        }

        if (isset($_POST['edit']))
        {
            $lnSegments = [];
            $lnSegments = explode("-", $html_element['lastname']->getValue());
            $toImplode = [];
            foreach($lnSegments as $segments)
            {
                $toImplode[] = trim($validate->my_ucwords($segments));
            }
            $lastname = implode("-", $toImplode);
            $lastname = str_ireplace($replace_chars, "", $lastname);
            $html_element['lastname']->setValue($lastname);
            $html_element['firstname']->setValue(trim($validate->my_ucwords($html_element['firstname']->getValue())));
            $html_element['middlename']->setValue(trim($validate->my_ucwords($html_element['middlename']->getValue())));
            $html_element['login']->setValue(trim($html_element['login']->getValue()));


            $html_element['lastname']->check();
            $html_element['firstname']->check();
            $html_element['middlename']->check();
            $html_element['login']->check();
            $html_element['email']->check();

            if (!$html_element['lastname']->getCheck())
            {
                $errors['lastname'] = 'Ошибка в поле "'.$html_element['lastname']->getCaption().'".';
            }
            if (!$html_element['firstname']->getCheck())
            {
                $errors['firstname'] = 'Ошибка в поле "'.$html_element['firstname']->getCaption().'".';
            }
            if (!$html_element['middlename']->getCheck())
            {
                $errors['middlename'] = 'Ошибка в поле "'.$html_element['middlename']->getCaption().'".';
            }
            if (!User::checkLogin($html_element['login']->getValue()))
            {
                if ($uid != $user['id'])
                {
                    $html_element['login']->setCheck(false);
                    $errors['login_db'] = 'Пользователь с таким логином уже зарегистрирован';
                }
            }
            if (!$validate->checkLogin($html_element['login']->getValue()))
            {
                $html_element['login']->setCheck(false);
            }
            if (!$html_element['login']->getCheck())
            {
                $errors['login'] = 'Ошибка в поле "'. $html_element['login']->getCaption() .'".<br />
                Необходимо заполнить от 6 до 32 символов.';
            }
            if (!empty($html_element['email']->getValue()))
            {
                $html_element['email']->setValue(trim($html_element['email']->getValue()));
                if (!$validate->checkEmail($html_element['email']->getValue()))
                {
                    $html_element['email']->setCheck(false);
                }
                if (!$html_element['email']->getCheck())
                {
                    $errors['email'] = 'Ошибка в поле "'. $html_element['email']->getCaption() .'".';
                }
            }

            if ($errors === false)
            {
                $user['lastname'] = $html_element['lastname']->getValue();
                $user['firstname'] = $html_element['firstname']->getValue();
                $user['middlename'] = $html_element['middlename']->getValue();
                $user['login'] = $html_element['login']->getValue();
                $user['email'] = $html_element['email']->getValue();
                $user['change_datetime'] = $date_time->format('Y-m-d H:i:s');
                $user['change_user_id'] = $u_id;
                $user['flag'] = $option_flag_select;
                User::edit($user);
                header('Location: /user/index?'.$url_param);
            }
        }

        if ($is_can)
        {
            include_once APP_VIEWS.'user/edit.php';
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
            if ($u_r['right_name'] == CAN_MODERATOR_USER)
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
                header('Location: /user/index?'.$url_param);
            }
        }
        $url_param .= '&page='.$page;
        if (isset($_POST['no']))
        {
            header('Location: /user/index?'.$url_param);
        }

        if ($is_can)
        {
            include_once APP_VIEWS.'user/delete.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }

    public function actionPassword()
    {
        $user_right = parent::getUserRight();
        $validate = new App_Validate();
        $url_param = '';
        $is_can = false;
        $search = [];
        $page = 1;
        $errors = false;
        $date_time = new DateTime();
        $user = [];
        $uid = null;
        $u_id = User::checkLogged();

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

        $url_param .= 's_name='.$search['name'].'&page='.$page;

        $user = User::getUser($uid);

        $html_element['password'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['password']->setName('password');
        $html_element['password']->setId('password');
        $html_element['password']->setMin(6);
        $html_element['password']->setMax(40);
        $html_element['password']->setCaption('Пароль');
        $html_element['password']->setConfig('type', 'password');
        $html_element['password']->setConfig('class', 'uk-width-1-1');
        $html_element['password']->setConfig('placeholder', 'Пароль');
        $html_element['password']->setValueFromRequest();

        if ($user['flag'] == FLAG_NO_CHANGE && $user['id'] != $u_id)
        {
            $errors['no_change'] = 'Невозможно изменить данного пользователя';
            $html_element['password']->setConfig('disabled', 'disabled');
        }

        if (isset($_POST['edit']))
        {
            $html_element['password']->check();

            if (!$validate->checkPassword($html_element['password']->getValue()))
            {
                $html_element['password']->setCheck(false);
            }
            if (!$html_element['password']->getCheck())
            {
                $errors['password'] = 'Ошибка в поле "'. $html_element['password']->getCaption() .'".<br />
                Необходимо заполнить от 6 до 20 символов.';
            }

            if ($errors === false)
            {
                $user['password'] = md5($html_element['password']->getValue());
                $user['change_user_id'] = $u_id;
                $user['change_datetime'] = $date_time->format('Y-m-d H:i:s');
                User::editPassword($user);
                header('Location: /user/index?'.$url_param);
            }
        }

        if ($is_can)
        {
            include_once APP_VIEWS.'user/password.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }
}