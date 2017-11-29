<?php


class MainController extends BaseController
{
    public function actionIndex()
    {
        if (!USER_ID)
        {
            header('Location: /main/login');
        }
        $user_right = parent::getUserRight();
        $app_state = new App_State();
        $is_can = false;
        $errors = false;
        $search = [];
        $page = 1;
        $index_number = 1;
        $directions = [];
        $option_direction_selected = null;
        $testing_list = [];
        $testing_results = [];
        $total = 0;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_TESTING_PASS)
            {
                $is_can = true;
                break;
            }
        }

        if (isset($_GET['s_direction']))
        {
            $option_direction_selected = htmlspecialchars($_GET['s_direction']);
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

        $html_element['name'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['name']->setName('s_name');
        $html_element['name']->setId('s_name');
        $html_element['name']->setCaption('Тестирование');
        $html_element['name']->setConfig('type', 'text');
        $html_element['name']->setConfig('class', 'uk-width-1-1');
        $html_element['name']->setConfig('placeholder', 'Тестирование');
        $html_element['name']->setValueFromRequest();

        if ($option_direction_selected > 0)
        {
            $search['direction_id'] = $option_direction_selected;

            if ($html_element['name']->getValue() != null)
            {
                $search['name'] = trim($html_element['name']->getValue());
            }
            $search['user_id'] = USER_ID;

            $testing_list = User_Testing::getTestingListBySearchParam($search, $page);
            foreach ($testing_list as $tl_item)
            {
                $search_result['user_id'] = USER_ID;
                $search_result['testing_id'] = $tl_item['testing_id'];
                $search_result['user_group_id'] = $tl_item['user_group_id'];
                $res_count = Testing_Result::getUserTestingCount($search_result);
                $testing_results[] = [
                    'testing_id' => $tl_item['testing_id'],
                    'user_group_id' => $tl_item['user_group_id'],
                    'count' => $res_count
                ];
            }
            $total = User_Testing::getTotalTestingListBySearchParam($search);
            $index_number = User_Testing::getIndexNumber($page);
            $pagination = new Pagination($total, $page, User_Testing::SHOW_BY_DEFAULT, 'page=');

            if (isset($_POST['start']))
            {
                $testing_state = Testing::getSessionTestingState();
                if ($testing_state == true)
                {
                    $testing_begin_info = array();
                    Testing::startTesting($testing_begin_info);
                    goto _gt_view;
                }

                $user_or_testing_id = htmlspecialchars($_POST['start']);
                $user_testing = User_Testing::getUserTestingByID($user_or_testing_id);
                if ($user_testing['user_id'] == $search['user_id']
                    && $user_testing['testing_id'] != null
                    && $user_testing['user_group_id'] != null)
                {
                    $testing_count = 0;
                    if (is_array($testing_results) && count($testing_results) > 0)
                    {
                        foreach ($testing_results as $tr_item)
                        {
                            if ($tr_item['testing_id'] == $user_testing['testing_id']
                                && $user_testing['user_group_id'])
                            {
                                $testing_count = $tr_item['count'];
                                break;
                            }
                        }
                    }

                    $testing_result['user_id'] = $user_testing['user_id'];
                    $testing_result['testing_id'] = $user_testing['testing_id'];
                    $testing_result['user_group_id'] = $user_testing['user_group_id'];
                    $date_time = new DateTime();
                    $testing_result['begin_datetime'] = $date_time->format('Y-m-d H:i:s');
                    /*$testing_result['change_user_id	'] = $user_testing['user_id'];
                    $testing_result['change_datetime'] = $date_time->format('Y-m-d H:i:s');*/
                    $testing_result['flag'] = '1';
                    $testing_begin_info['testing_result'] = $testing_result;
                    $testing = Testing::getTesting($testing_result['testing_id']);
                    $testing_begin_info['testing'] = $testing;
                    $questions = null;
                    if ($testing['testing_count'] > $testing_count)
                    {
                        if (is_array($testing) && $testing['test_id'] != null)
                        {
                            $questions = Question::getQuestionsByTest($testing['test_id']);
                        }
                        else
                        {
                            $errors['no_testing'] = 'Не удалось получить информацию о тестировании. Обратитесь к администратору за помощью';
                        }
                    }
                    else
                    {
                        $errors['testing_count'] = 'Превышен лимит прохождения данного тестирования';
                    }
                    if ($testing['question_count'] > count($questions))
                    {
                        $errors['no_questions'] = 'Недостаточно вопросов для прохождения тестирования. Обратитесь к администратору за помощью';
                    }
                    $testing_begin_info['questions'] = $questions;

                    if ($errors === false)
                    {
                        $testing_result_id = Testing_Result::add($testing_result);
                        if (!$testing_result_id)
                        {
                            $errors['add_testing_result'] = 'Не удалось внести информацию, необходимую для тестирования';
                        }
                        if ($errors === false)
                        {
                            $testing_begin_info['testing_result_id'] = $testing_result_id;
                            $start_testing = Testing::startTesting($testing_begin_info);
                            if (!$start_testing)
                            {
                                $errors['start_testing'] = 'Не удалось начать тестирование. Обратитесь к администратору';
                            }
                        }
                    }
                }
                else
                {
                    $errors['user_testing'] = 'Вы не можете проходить выбранное тестирование. Обратитесь к администратору за помощью';
                }
            }
        }

        _gt_view:
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
                    $app_directory = new App_Directory();
                    $dir_path = '/temp/users';
                    $temp_user_dir = ROOT.$dir_path.'/'.$u_id;
                    // Удаляем директорию, если она есть
                    $app_directory->removeDirectory($temp_user_dir);
                    if (!mkdir($temp_user_dir, 0777, true))
                    {
                        $errors['not_dir'] = 'Не удалось создать временную директорию пользователя';
                    }
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
                $new_user['flag'] = 1;

                $new_user_id = User::add($new_user);
                if ($new_user_id !== false)
                {
                    User::setDefaultUserRight($new_user_id);
                    User::auth($new_user_id);
                    $app_directory = new App_Directory();
                    $dir_path = '/temp/users';
                    $temp_user_dir = ROOT.$dir_path.'/'.$new_user_id;
                    // Удаляем директорию, если она есть
                    $app_directory->removeDirectory($temp_user_dir);
                    if (!mkdir($temp_user_dir, 0777, true))
                    {
                        $errors['not_dir'] = 'Не удалось создать временную директорию пользователя';
                    }
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

    public function actionQuiz()
    {
        $user_right = parent::getUserRight();
        $is_can = false;
        $errors = false;
        $search = [];
        $questions = []; // Вопросы из сессии
        $answers = []; // Ответы из сессии
        $testing = []; // Информация о тестирвоании
        $question = []; // Информация о вопросе
        $question_answers = []; // Ответы к вопросу
        $question_number = 0; // Номер текущего вопроса
        $question_count = 0; // Общее количество вопросов
        $qid = null; // ID вопроса
        $progress_percentagle = 0; // Прогресс прохождения
        $datetime_is_day_view = false; // Показывать ли дни в при отсчете времени тестирования
        $modal_message = ''; // Сообщение для модального окна
        $is_testing_complete = false; // Тестирование пройдено

        $answered_question_numbers = null;

        $img_src = '';
        $is_question_answered = false;

        $is_question_time_ok = true;
        $is_question_complete_ok = true;


        $testing_start_time = Testing::getSessionTestingStartTime();
        $date_time = new DateTime($testing_start_time);

        $testing_countdown = null;
        $question_countdown = null;

        $tid = Testing::getSessionTesting(); // ID тестирования

        if ($tid != false)
        {
            $testing = Testing::getTesting($tid);
        }
        else{
            $errors['testing_id'] = 'Не удалось получить информацию о тестировании';
        }

        if (isset($_GET['qid']))
        {
            $qid = htmlspecialchars($_GET['qid']);
        }

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_TESTING_PASS)
            {
                $is_can = true;
                break;
            }
        }

        $questions = Testing::getSessionTestingQuestions();
        $question_count = count($questions);
        $answers = Testing::getSessionTestingAnswers();

        if (in_array($qid, $questions))
        {
            if ($testing['testing_time_flag'] == FLAG_ON)
            {
                if ($testing['testing_time'] != null)
                {
                    $hours = 0;
                    $minutes = 0;
                    $seconds = 0;
                    $segments = explode(':', $testing['testing_time']);
                    if (count($segments) == 3)
                    {
                        $hours = intval($segments[0]);
                        $minutes = intval($segments[1]);
                        $seconds = intval($segments[2]);
                    }
                    if ($hours > 23)
                    {
                        $datetime_is_day_view = true;
                    }
                    $date_time->add(new DateInterval('PT'.$hours.'H'.$minutes.'M'.$seconds.'S'));
                    $testing_countdown = $date_time->format('Y/m/d H:i:s');
                }
            }

            $question = Question::getQuestion($qid);

            if ($question['question_time_flag'] == FLAG_ON)
            {
                if ($question['question_time'] != null)
                {
                    $hours = 0;
                    $minutes = 0;
                    $seconds = 0;
                    $segments = explode(':', $question['question_time']);
                    if (count($segments) == 3)
                    {
                        $hours = intval($segments[0]);
                        $minutes = intval($segments[1]);
                        $seconds = intval($segments[2]);
                    }
                    if ($hours > 23)
                    {
                        $datetime_is_day_view = true;
                    }
                    $date_time->add(new DateInterval('PT'.$hours.'H'.$minutes.'M'.$seconds.'S'));
                    $question_countdown = $date_time->format('Y/m/d H:i:s');
                }
            }

            $img_src = 'http://quiz-v2/app/templates/images/questions/'.$question['path_img'];
            $question_number = array_search($qid, $questions);
            $question_number++;
            $question_answers = Answer::getAnswers($qid);

            $question_start_datetime = new DateTime();

            if ($question['question_time_flag'] == FLAG_ON)
            {

            }



            if (isset($_POST['skip']))
            {
                if ($question_number != $question_count)
                {
                    $next_qid = $questions[$question_number];
                    if ($next_qid != null)
                    {
                        header('Location: /main/quiz?qid='.$next_qid);
                    }
                }
            }

            if (isset($_POST['respond']))
            {
                $next_qid = null;
                $respond_question_answers = null;
                if ($question_number != $question_count)
                {
                    $next_qid = $questions[$question_number];
                }

                if (isset($_POST['answer']))
                {
                    if (intval($_POST['answer']) > 0)
                    {
                        $respond_question_answers[0] = intval($_POST['answer']);
                    }
                }

                if (isset($_POST['answers']))
                {
                    if (is_array($_POST['answers']))
                    {
                        $temp_array = null;
                        foreach ($_POST['answers'] as $p_item)
                        {
                            $p_item = intval($p_item);
                            if ($p_item > 0)
                            {
                                $temp_array[] = $p_item;
                            }
                        }
                    }
                    $respond_question_answers = $temp_array;
                }

                if ($is_question_time_ok)
                {
                    Testing::setSessionAnswerRespond($question_number, $qid, $respond_question_answers);
                    if ($question_number != $question_count)
                    {
                        $next_qid = $questions[$question_number];
                        if ($next_qid != null)
                        {
                            header('Location: /main/quiz?qid='.$next_qid);
                        }
                    }
                }
                else
                {
                    $errors['question_time_is_over'] = 'Ответ не будет засчитан. <br />Время истекло для овета на данный вопрос истекло';
                }
            }

            if (isset($_POST['previous']))
            {
                if ($question_number > 1)
                {
                    $previous_qid = $questions[$question_number-2];
                    if ($previous_qid != null)
                    {
                        header('Location: /main/quiz?qid='.$previous_qid);
                    }
                }
            }

            $answers = Testing::getSessionTestingAnswers();
            $n = 0;
            if ($question['question_type_id'] == QUESTION_TYPE_ONE_TO_ONE)
            {
                foreach ($question_answers as $qa_item)
                {
                    $html_element['answer_'.$n] = new \HTMLElement\HTMLCheckboxAndRadioRadioElement();
                    $html_element['answer_'.$n]->setName('answer');
                    $html_element['answer_'.$n]->setId('answer_'.$n);
                    $html_element['answer_'.$n]->setValue($qa_item['id']);
                    $html_element['answer_'.$n]->setCaption($qa_item['name']);
                    $html_element['answer_'.$n]->setConfig('style', 'margin-right: 5px;');
                    $html_element['answer_'.$n]->setConfig('onchange', 'changeButtonState()');
                    foreach ($answers as $a_key => $a_array)
                    {
                        if ($a_key == $question_number)
                        {
                            if ($a_array[$qid][0] == $html_element['answer_'.$n]->getValue())
                            {
                                $html_element['answer_'.$n]->setChecked(true);
                                $is_question_answered = true;
                                break;
                            }
                        }
                    }
                    $n++;
                }
            }

            if ($question['question_type_id'] == QUESTION_TYPE_ONE_TO_MANY)
            {
                foreach ($question_answers as $qa_item)
                {
                    $html_element['answer_'.$n] = new \HTMLElement\HTMLCheckboxAndRadioCheckboxElement();
                    $html_element['answer_'.$n]->setName('answers['.$qa_item['id'].']');
                    $html_element['answer_'.$n]->setId('answer_'.$n);
                    $html_element['answer_'.$n]->setValue($qa_item['id']);
                    $html_element['answer_'.$n]->setCaption($qa_item['name']);
                    $html_element['answer_'.$n]->setConfig('style', 'margin-right: 5px;');
                    $html_element['answer_'.$n]->setConfig('onchange', 'changeButtonState()');
                    foreach ($answers as $a_key => $a_array)
                    {
                        if ($a_key == $question_number)
                        {
                            foreach ($a_array as $aa_key => $aa_array)
                            {
                                foreach ($aa_array as $aa_item)
                                {
                                    if ($aa_item == $html_element['answer_'.$n]->getValue())
                                    {
                                        $html_element['answer_'.$n]->setChecked(true);
                                        $is_question_answered = true;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    $n++;
                }
            }

            foreach ($answers as $a_key => $a_array)
            {
                foreach ($a_array as $aa_key => $aa_array)
                {
                    if ($aa_array != null)
                    {
                        $answered_question_numbers[] = $a_key;
                    }
                }
            }
            $progress_percentagle = (count($answered_question_numbers)/$question_count) * 100;
            if ($progress_percentagle == 100)
            {
                $is_testing_complete = true;
                $modal_message = 'Вы ответили на все вопросы.';
            }
            if (isset($_POST['complete']))
            {
                echo 'Нажато Завершить';
                if ($is_question_complete_ok)
                {
                    // Заврешить
                }
            }
        }
        else
        {
            $errors['question'] = 'Данного вопроса не существует';
        }


        if ($is_can)
        {
            include_once APP_VIEWS.'main/quiz.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }
}