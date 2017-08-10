<?php


class MainController extends BaseController
{
    public function actionIndex()
    {
        //include_once ROOT . '/config/rights_rules.php';

        if (!USER_ID)
        {
            header('Location: /main/login');
        }



        include_once APP_VIEWS.'main/index.php';
    }

    public function actionLogin()
    {
        $errors = false;

        $html_element['login'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['login']->setConfig('type', 'text');
        $html_element['login']->setConfig('class', 'uk-width-1-1');
        $html_element['login']->setName('login');
        $html_element['login']->setId('login');
        $html_element['login']->setConfig('placeholder', 'Логин');
        $html_element['login']->setValueFromRequest();


        $html_element['password'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['password']->setConfig('type', 'password');
        $html_element['password']->setConfig('class', 'uk-width-1-1');
        $html_element['password']->setName('password');
        $html_element['password']->setId('password');
        $html_element['password']->setConfig('placeholder', 'Пароль');
        $html_element['password']->setValueFromRequest();

        if (isset($_POST['enter']))
        {
            function getCurlData($url)
            {
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_TIMEOUT, 10);
                $curlData = curl_exec($curl);
                curl_close($curl);
                return $curlData;
            }


            $user_ip = $_SERVER["REMOTE_ADDR"];
            $secret = '6Lf6myAUAAAAAGbUu-3Q6xFMirtxLzIvzccA8OiE';
            $recaptcha = $_POST['g-recaptcha-response'];
            $google_url = 'https://www.google.com/recaptcha/api/siteverify';
            $url = $google_url.'?secret='.$secret
                .'&response='.$recaptcha
                .'&remoteip='.$user_ip;

            $res = getCurlData($url);
            $res= json_decode($res, true);
            if (!$res['success'])
            {
                $errors['no_captcha'] = 'Не пройдена проверка "Я не робот"';
                goto _gt_view;
            }

            $user_data = [];
            $validate = new App_Validate();
            if (!$validate->checkLogin($html_element['login']->getValue())
                || !$validate->checkPassword($html_element['password']->getValue())
            )
            {
                $errors['login_or_password'] = 'Данные для входа заданы не верно.';
            }

            if ($errors === false)
            {
                $user_data['login'] = $html_element['login']->getValue();
                $user_data['password'] = md5($html_element['password']->getValue());
                $u_id = User::checkUserData($user_data);
                if ($u_id !== false)
                {
                    User::auth($u_id);
                    header('Location: /main/index');
                }
                else
                {
                    $errors['not_user'] = 'Данные для входа заданы не верно.';
                }
            }
        }

        _gt_view:
        include_once APP_VIEWS . 'main/login.php';
    }

    public function actionLogout()
    {
        User::logout();
    }

    public function actionRegistration()
    {
        $errors = false;
        $validate = new App_Validate();
        $date_time = new DateTime();
        $replace_chars = include (ROOT . '/config/replace_chars.php');

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


        function getCurlData($url)
        {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, 10);
            $curlData = curl_exec($curl);
            curl_close($curl);
            return $curlData;
        }

        if (isset($_POST['registration']))
        {
            $user_ip = $_SERVER["REMOTE_ADDR"];
            $secret = '6Lf6myAUAAAAAGbUu-3Q6xFMirtxLzIvzccA8OiE';
            $recaptcha = $_POST['g-recaptcha-response'];
            $google_url = 'https://www.google.com/recaptcha/api/siteverify';
            $url = $google_url.'?secret='.$secret
                .'&response='.$recaptcha
                .'&remoteip='.$user_ip;

            $res = getCurlData($url);
            $res= json_decode($res, true);
            if (!$res['success'])
            {
                $errors['no_captcha'] = 'Не пройдена проверка "Я не робот"';
                goto _gt_view;
            }

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
                $new_user = [];
                $new_user['registered_datetime'] = $date_time->format('Y-m-d H:i:s');
                $new_user['lastname'] = $html_element['lastname']->getValue();
                $new_user['firstname'] = $html_element['firstname']->getValue();
                $new_user['middlename'] = $html_element['middlename']->getValue();
                $new_user['login'] = $html_element['login']->getValue();
                $new_user['password'] = md5($html_element['password']->getValue());
                $new_user['email'] = $html_element['email']->getValue();

                $new_user_id = User::add($new_user);
                if ($new_user_id !== false)
                {
                    User::setDefaultUserRight($new_user_id);
                    User::auth($new_user_id);
                    header('Location: /main/index');
                }
                else
                {
                    $errors['no_registration'] = 'Не удалось зарегистрировать';
                }
            }
        }

        _gt_view:
        include_once APP_VIEWS . 'main/registration.php';
    }
}