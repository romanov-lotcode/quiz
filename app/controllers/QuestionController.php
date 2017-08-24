<?php

class QuestionController extends BaseController
{
    public function actionIndex()
    {
        $user_right = parent::getUserRight();
        $app_state = new App_State();
        $url_param = '';
        $is_can = false;
        $is_can_test = false;
        $is_can_answer = false;
        $search = [];
        $page = 1;
        $index_number = 0;
        $questions = [];
        $total = 0;
        $test = null;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_QUESTION)
            {
                $is_can = true;
            }
            if ($u_r['right_name'] == CAN_MODERATOR_TEST)
            {
                $is_can_test = true;
            }
            if ($u_r['right_name'] == CAN_MODERATOR_ANSWER)
            {
                $is_can_answer = true;
            }
            if ($is_can === true && $is_can_test === true && $is_can_answer === true)
            {
                break;
            }
        }

        if (isset($_GET['s_direction']))
        {
            $search['direction_id'] = htmlspecialchars($_GET['s_direction']);
        }
        if (isset($_GET['tid']))
        {
            $search['test_id'] = htmlspecialchars($_GET['tid']);
        }
        if (isset($_GET['s_name']))
        {
            $search['test_name'] = htmlspecialchars($_GET['s_name']);
        }
        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }

        if ($page < 1)
        {
            $page = 1;
        }

        if (isset($_GET['s_q_name']))
        {
            $search['name'] = htmlspecialchars($_GET['s_q_name']);
        }

        $test = Test::getTest($search['test_id']);

        $html_element['name'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['name']->setName('s_q_name');
        $html_element['name']->setId('s_q_name');
        $html_element['name']->setCaption('Вопрос');
        $html_element['name']->setConfig('type', 'text');
        $html_element['name']->setConfig('class', 'uk-width-1-1');
        $html_element['name']->setConfig('placeholder', 'Вопрос');
        $html_element['name']->setValueFromRequest();

        if (isset($search['test_id']) && $search['test_id'] != null)
        {
            if ($html_element['name']->getValue() != null)
            {
                $search['name'] = trim($html_element['name']->getValue());
            }

            $questions = Question::getQuestions($search);
            $total = Question::getTotalQuestions($search);
        }


        if ($is_can)
        {
            $url_param .= 's_direction='.$search['direction_id'].'&s_name='.$search['test_name']
                .'&tid='.$search['test_id'].'&page='.$page.'&s_q_name='.$search['name'];

            include_once APP_VIEWS.'question/index.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }

    public function actionAdd()
    {
        $user_right = parent::getUserRight();
        $app_validate = new App_Validate();
        $url_param = '';
        $is_can = false;
        $search = [];
        $page = 1;
        $errors = false;
        $date_time = new DateTime();
        $question = [];
        $test = null;

        $u_id = User::checkLogged();

        $full_file_path = '';

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_QUESTION)
            {
                $is_can = true;
                break;
            }
        }

        if (isset($_GET['s_direction']))
        {
            $search['direction_id'] = htmlspecialchars($_GET['s_direction']);
        }
        if (isset($_GET['tid']))
        {
            $search['test_id'] = htmlspecialchars($_GET['tid']);
        }
        if (isset($_GET['s_name']))
        {
            $search['test_name'] = htmlspecialchars($_GET['s_name']);
        }
        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }

        if ($page < 1)
        {
            $page = 1;
        }

        if (isset($_GET['s_q_name']))
        {
            $search['name'] = htmlspecialchars($_GET['s_q_name']);
        }

        $url_param .= 's_direction='.$search['direction_id'].'&s_name='.$search['test_name']
            .'&tid='.$search['test_id'].'&page='.$page.'&s_q_name='.$search['name'];

        $test = Test::getTest($search['test_id']);

        $html_element['name'] = new \HTMLElement\HTMLTextTextareaElement();
        $html_element['name']->setName('name');
        $html_element['name']->setId('name');
        $html_element['name']->setMin(1);
        $html_element['name']->setMax(1000);
        $html_element['name']->setCaption('Вопрос');
        $html_element['name']->setConfig('rows', '5');
        $html_element['name']->setConfig('class', 'uk-width-1-1');
        $html_element['name']->setConfig('placeholder', 'Вопрос');
        $html_element['name']->setValueFromRequest();

        $html_element['number'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['number']->setName('number');
        $html_element['number']->setId('number');
        $html_element['number']->setValue(0);
        $html_element['number']->setMax(6);
        $html_element['number']->setCaption('Номер');
        $html_element['number']->setConfig('type', 'number');
        $html_element['number']->setConfig('min', '0');
        $html_element['number']->setConfig('max', '999999');
        $html_element['number']->setConfig('class', 'uk-width-1-4');
        $html_element['number']->setConfig('placeholder', 'Номер');
        $html_element['number']->setValueFromRequest();

        $option_question_type_selected = 0;
        $option_question_type = [];
        $optgroup_question_type = [];

        if (isset($_POST['question_type']))
        {
            $option_question_type_selected = $_POST['question_type'];
            $option_question_type_selected = intval($option_question_type_selected);
            if ($option_question_type_selected != 0
                && $option_question_type_selected != 1)
            {
                $option_question_type_selected = 0;
            }
        }

        $i = 0;
        $option_question_type[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_question_type[$i]->setValue(0);
        $option_question_type[$i]->setText('Один к одному');
        ($option_question_type_selected == $option_question_type[$i]->getValue())? $option_question_type[$i]->setSelected(true):'';

        $i = 1;
        $option_question_type[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_question_type[$i]->setValue(1);
        $option_question_type[$i]->setText('Один ко многим');
        ($option_question_type_selected == $option_question_type[$i]->getValue())? $option_question_type[$i]->setSelected(true):'';

        $html_element['question_type'] = new \HTMLElement\HTMLSelectElement();
        $html_element['question_type']->setCaption('Тип вопроса');
        $html_element['question_type']->setConfig('class', 'uk-width-1-2');
        $html_element['question_type']->setName('question_type');
        $html_element['question_type']->setId('question_type');
        $html_element['question_type']->setNecessarily(true);

        $html_element['explanation'] = new \HTMLElement\HTMLTextTextareaElement();
        $html_element['explanation']->setName('explanation');
        $html_element['explanation']->setId('explanation');
        $html_element['explanation']->setMax(1000);
        $html_element['explanation']->setCaption('Объяснение');
        $html_element['explanation']->setConfig('rows', '5');
        $html_element['explanation']->setConfig('class', 'uk-width-1-1');
        $html_element['explanation']->setConfig('placeholder', 'Объяснение');
        $html_element['explanation']->setValueFromRequest();

        $html_element['comment'] = new \HTMLElement\HTMLTextTextareaElement();
        $html_element['comment']->setName('comment');
        $html_element['comment']->setId('comment');
        $html_element['comment']->setMax(1000);
        $html_element['comment']->setCaption('Комментарий');
        $html_element['comment']->setConfig('rows', '5');
        $html_element['comment']->setConfig('class', 'uk-width-1-1');
        $html_element['comment']->setConfig('placeholder', 'Комментарий');
        $html_element['comment']->setValueFromRequest();

        $html_element['hour'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['hour']->setName('hour');
        $html_element['hour']->setId('hour');
        $html_element['hour']->setValue(0);
        $html_element['hour']->setMax(3);
        $html_element['hour']->setCaption('часы');
        $html_element['hour']->setConfig('type', 'number');
        $html_element['hour']->setConfig('min', '0');
        $html_element['hour']->setConfig('max', '838');
        $html_element['hour']->setConfig('class', 'uk-width-1-2');
        $html_element['hour']->setConfig('placeholder', 'чч');
        $html_element['hour']->setValueFromRequest();

        $html_element['minute'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['minute']->setName('minute');
        $html_element['minute']->setId('minute');
        $html_element['minute']->setValue(0);
        $html_element['minute']->setMax(2);
        $html_element['minute']->setCaption('минуты');
        $html_element['minute']->setConfig('type', 'number');
        $html_element['minute']->setConfig('min', '0');
        $html_element['minute']->setConfig('max', '59');
        $html_element['minute']->setConfig('class', 'uk-width-1-2');
        $html_element['minute']->setConfig('placeholder', 'мм');
        $html_element['minute']->setValueFromRequest();

        $html_element['second'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['second']->setName('second');
        $html_element['second']->setId('second');
        $html_element['second']->setValue(0);
        $html_element['second']->setMax(2);
        $html_element['second']->setCaption('секунды');
        $html_element['second']->setConfig('type', 'number');
        $html_element['second']->setConfig('min', '0');
        $html_element['second']->setConfig('max', '59');
        $html_element['second']->setConfig('class', 'uk-width-1-2');
        $html_element['second']->setConfig('placeholder', 'сс');
        $html_element['second']->setValueFromRequest();

        $option_question_time_flag_select = APP_NO;
        $option_question_time_flag = [];
        $optgroup_question_time_flag = [];

        if (isset($_POST['question_time_flag']))
        {
            $option_question_time_flag_select = $_POST['question_time_flag'];
            $option_question_time_flag_select = intval($option_question_time_flag_select);
            if ($option_question_time_flag_select != APP_NO
                && $option_question_time_flag_select != APP_YES)
            {
                $option_question_time_flag_select = APP_NO;
            }
        }

        $i = 0;
        $option_question_time_flag[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_question_time_flag[$i]->setValue(APP_YES);
        $option_question_time_flag[$i]->setText('Да');
        ($option_question_time_flag_select == $option_question_time_flag[$i]->getValue())? $option_question_time_flag[$i]->setSelected(true):'';

        $i = 1;
        $option_question_time_flag[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_question_time_flag[$i]->setValue(APP_NO);
        $option_question_time_flag[$i]->setText('Нет');
        ($option_question_time_flag_select == $option_question_time_flag[$i]->getValue())? $option_question_time_flag[$i]->setSelected(true):'';

        $html_element['question_time_flag'] = new \HTMLElement\HTMLSelectElement();
        $html_element['question_time_flag']->setCaption('Включить время');
        $html_element['question_time_flag']->setConfig('class', 'uk-width-1-4');
        $html_element['question_time_flag']->setConfig('onchange', "show_hide('question_time_flag', 'q_time');");
        $html_element['question_time_flag']->setName('question_time_flag');
        $html_element['question_time_flag']->setId('question_time_flag');
        $html_element['question_time_flag']->setNecessarily(true);

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
        $option_flag[$i]->setText('Вкл');
        ($option_flag_select == $option_flag[$i]->getValue())? $option_flag[$i]->setSelected(true):'';

        $i = 1;
        $option_flag[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_flag[$i]->setValue(FLAG_OFF);
        $option_flag[$i]->setText('Выкл');
        ($option_flag_select == $option_flag[$i]->getValue())? $option_flag[$i]->setSelected(true):'';

        $html_element['flag'] = new \HTMLElement\HTMLSelectElement();
        $html_element['flag']->setCaption('Состояние');
        $html_element['flag']->setConfig('class', 'uk-width-1-4');
        $html_element['flag']->setName('flag');
        $html_element['flag']->setId('flag');

        if (isset($_POST['p_i']))
        {
            $question['path_img'] = htmlspecialchars($_POST['p_i']);
        }

        if (isset($_POST['add']))
        {
            /**
             * Обработка изображения Begin
             */
            $user_dir = ROOT.'\\temp\\users\\'.$u_id;

            $types = array('image/gif' => '.gif', 'image/png' => '.png', 'image/jpeg' => '.jpg');

            $file_max_size = '3145728'; // 3Мб
            $file_max = 0;

            // Получаем максимальное значение файла из настроек php.ini
            $file_max_size_php_ini = ini_get('post_max_size');

            $file_max_size_php_ini = $this->parse_size($file_max_size_php_ini);
            if ((int)$file_max_size <= (int)$file_max_size_php_ini)
            {
                $file_max = $file_max_size;
            }
            else
            {
                $file_max = $file_max_size_php_ini;
            }

            $file_type = '';
            $file_name = '';

            if ($_FILES['path_img']['error'] == UPLOAD_ERR_OK)
            {
                $i = 1;
                foreach ($types as $key => $value)
                {
                    if ($key == $_FILES['path_img']['type'] && $_FILES['path_img']['size'] <= $file_max)
                    {
                        $file_type = $value;
                        $file_name = 'path_img'.$file_type;
                        break;
                    }
                    $i++;
                }
                if ($i == count($types)+1)
                {
                    $errors['path_img'] = 'Изображение должно быть формата .jpg, .png или .gif';
                }
            }
            if ($_FILES['path_img']['size'] > $file_max)
            {
                $errors['path_img'] = 'Изображение не должно превышать 3 Мб';
            }
            if ($file_name != '')
            {
                $full_file_path = $user_dir.'\\'.$file_name;
                move_uploaded_file($_FILES['path_img']['tmp_name'], $full_file_path);
                $question['path_img'] = $file_name;
            }
            /**
             * Обработка изображения End
             */

            $html_element['name']->setValue($html_element['name']->getValue());
            $html_element['number']->setValue($html_element['number']->getValue());
            $html_element['explanation']->setValue($html_element['explanation']->getValue());
            $html_element['comment']->setValue($html_element['comment']->getValue());

            $html_element['name']->check();
            $html_element['number']->check();
            $html_element['explanation']->check();
            $html_element['comment']->check();

            if ($option_question_time_flag_select == APP_YES)
            {
                $html_element['hour']->setValue(trim(intval($html_element['hour']->getValue())));
                $html_element['minute']->setValue(trim(intval($html_element['minute']->getValue())));
                $html_element['second']->setValue(trim(intval($html_element['second']->getValue())));

                $html_element['hour']->check();
                $html_element['minute']->check();
                $html_element['second']->check();

                if ($html_element['hour']->getValue() == 0
                    && $html_element['minute']->getValue() == 0
                    && $html_element['second']->getValue() == 0)
                {
                    $errors['question_time'] = 'Вы включили время, но время для ответа на вопрос не задали.<br />Укажите часы или минуты, или секунды.';
                    $html_element['hour']->setCheck(false);
                    $html_element['minute']->setCheck(false);
                    $html_element['second']->setCheck(false);
                }
            }

            if (!$html_element['name']->getCheck())
            {
                $errors['name'] = 'Ошибка в поле "'.$html_element['name']->getCaption().'".<br />Не может быть такой длины.';
            }

            if (!$app_validate->checkInt($html_element['number']->getValue(), true, true, 0, 999999))
            {
                $html_element['number']->setCheck(false);
            }

            if (!$html_element['number']->getCheck())
            {
                $errors['number'] = 'Ошибка в поле "'.$html_element['number']->getCaption().'".<br />Должно быть целым числом от 0 до 999999.';
            }

            if (!$html_element['explanation']->getCheck())
            {
                $errors['explanation'] = 'Ошибка в поле "'.$html_element['explanation']->getCaption().'".<br />Не может быть такой длины.';
            }

            if (!$html_element['comment']->getCheck())
            {
                $errors['comment'] = 'Ошибка в поле "'.$html_element['comment']->getCaption().'".<br />Не может быть такой длины.';
            }

            if (!$app_validate->checkInt($html_element['hour']->getValue(), true, true, 0, 838))
            {
                $html_element['hour']->setCheck(false);
            }

            if (!$html_element['hour']->getCheck())
            {
                $errors['hour'] = 'Ошибка в поле "'.$html_element['hour']->getCaption().'".<br />Должно быть целым числом от 0 до 838.';
            }

            if (!$app_validate->checkInt($html_element['minute']->getValue(), true, true, 0, 59))
            {
                $html_element['minute']->setCheck(false);
            }

            if (!$html_element['minute']->getCheck())
            {
                $errors['minute'] = 'Ошибка в поле "'.$html_element['minute']->getCaption().'".<br />Должно быть целым числом от 0 до 59.';
            }

            if (!$app_validate->checkInt($html_element['second']->getValue(), true, true, 0, 59))
            {
                $html_element['second']->setCheck(false);
            }

            if (!$html_element['second']->getCheck())
            {
                $errors['second'] = 'Ошибка в поле "'.$html_element['second']->getCaption().'".<br />Должно быть целым числом от 0 до 59.';
            }

            if ($errors === false)
            {
                $time['hour'] = $html_element['hour']->getValue();
                $time['minute'] = $html_element['minute']->getValue();
                $time['second'] = $html_element['second']->getValue();
                $question['question_time'] = $app_validate->getTimeFromArrayInt($time);
                if (!$question['question_time'])
                {
                    $errors['question_time'] = 'Не удалось установить время';
                }


                if ($errors === false)
                {
                    $question['name'] = $html_element['name']->getValue();
                    $question['number'] = $html_element['number']->getValue();
                    $question['question_type_id'] = $option_question_type_selected;
                    $question['explanation'] = $html_element['explanation']->getValue();
                    $question['comment'] = $html_element['comment']->getValue();
                    $question['test_id'] = $search['test_id'];
                    $file_name = $question['path_img'];
                    $question['path_img'] = '';
                    // question_time значение передано выше
                    $question['question_time_flag'] = $option_question_time_flag_select;
                    $question['change_user_id'] = $u_id;
                    $question['change_datetime'] = $date_time->format('Y-m-d H:i:s');
                    $question['flag'] = $option_flag_select;

                    $is_add = Question::add($question);
                    if ($is_add !== false)
                    {
                        if ($file_name != null)
                        {
                            $file = $user_dir.'\\'.$file_name;
                            if (file_exists($file))
                            {
                                $file_type = '';
                                if (exif_imagetype($file) == IMAGETYPE_GIF)
                                {
                                    $file_type = '.gif';
                                }
                                if (exif_imagetype($file) == IMAGETYPE_JPEG)
                                {
                                    $file_type = '.jpg';
                                }
                                if (exif_imagetype($file) == IMAGETYPE_PNG)
                                {
                                    $file_type = '.png';
                                }
                                $name = $is_add.$file_type;
                                copy($user_dir.'\\'.$file_name, ROOT.'\\app\\templates\\images\\questions\\'.$name);
                                Question::updatePathImg($is_add, $name);
                                foreach ($types as $key => $value)
                                {
                                    if (file_exists($user_dir.'\\path_img'.$value))
                                    {
                                        unlink($user_dir.'\\path_img'.$value);
                                    }
                                }
                            }
                        }
                        header('Location: /question/index?'.$url_param);
                    }
                    else
                    {
                        $errors['add'] = 'Ничего не удалось добавить! Возможно у вас нет прав';
                    }
                }
            }
        }

        if ($is_can)
        {
            include_once APP_VIEWS.'question/add.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }

    public function actionEdit()
    {
        $user_right = parent::getUserRight();
        $app_validate = new App_Validate();
        $url_param = '';
        $is_can = false;
        $search = [];
        $page = 1;
        $errors = false;
        $date_time = new DateTime();
        $question = [];
        $qid = null;

        $u_id = User::checkLogged();

        $full_file_path = '';
        $is_new_file_name = false;
        $img_delete = false;
        $old_file = '';
        $img_src = '';
        $src_1 = 'http://quiz-v2/temp/users/'. $u_id .'/';
        $src_2 = 'http://quiz-v2/app/templates/images/questions/';

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_QUESTION)
            {
                $is_can = true;
                break;
            }
        }

        if (isset($_GET['s_direction']))
        {
            $search['direction_id'] = htmlspecialchars($_GET['s_direction']);
        }
        if (isset($_GET['tid']))
        {
            $search['test_id'] = htmlspecialchars($_GET['tid']);
        }
        if (isset($_GET['s_name']))
        {
            $search['test_name'] = htmlspecialchars($_GET['s_name']);
        }
        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }

        if ($page < 1)
        {
            $page = 1;
        }

        if (isset($_GET['s_q_name']))
        {
            $search['name'] = htmlspecialchars($_GET['s_q_name']);
        }

        if (isset($_GET['qid']))
        {
            $qid = htmlspecialchars($_GET['qid']);
        }

        $url_param .= 's_direction='.$search['direction_id'].'&s_name='.$search['test_name']
            .'&tid='.$search['test_id'].'&page='.$page.'&s_q_name='.$search['name'];

        $question = Question::getQuestion($qid);
        $old_file = $question['path_img'];

        $html_element['name'] = new \HTMLElement\HTMLTextTextareaElement();
        $html_element['name']->setName('name');
        $html_element['name']->setId('name');
        $html_element['name']->setValue($question['name']);
        $html_element['name']->setMin(1);
        $html_element['name']->setMax(1000);
        $html_element['name']->setCaption('Вопрос');
        $html_element['name']->setConfig('rows', '5');
        $html_element['name']->setConfig('class', 'uk-width-1-1');
        $html_element['name']->setConfig('placeholder', 'Вопрос');
        $html_element['name']->setValueFromRequest();

        $html_element['number'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['number']->setName('number');
        $html_element['number']->setId('number');
        $html_element['number']->setValue($question['number']);
        $html_element['number']->setMax(6);
        $html_element['number']->setCaption('Номер');
        $html_element['number']->setConfig('type', 'number');
        $html_element['number']->setConfig('min', '0');
        $html_element['number']->setConfig('max', '999999');
        $html_element['number']->setConfig('class', 'uk-width-1-4');
        $html_element['number']->setConfig('placeholder', 'Номер');
        $html_element['number']->setValueFromRequest();

        $option_question_type_selected = $question['question_type_id'];
        $option_question_type = [];
        $optgroup_question_type = [];

        if (isset($_POST['question_type']))
        {
            $option_question_type_selected = $_POST['question_type'];
            $option_question_type_selected = intval($option_question_type_selected);
            if ($option_question_type_selected != 0
                && $option_question_type_selected != 1)
            {
                $option_question_type_selected = 0;
            }
        }

        $i = 0;
        $option_question_type[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_question_type[$i]->setValue(0);
        $option_question_type[$i]->setText('Один к одному');
        ($option_question_type_selected == $option_question_type[$i]->getValue())? $option_question_type[$i]->setSelected(true):'';

        $i = 1;
        $option_question_type[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_question_type[$i]->setValue(1);
        $option_question_type[$i]->setText('Один ко многим');
        ($option_question_type_selected == $option_question_type[$i]->getValue())? $option_question_type[$i]->setSelected(true):'';

        $html_element['question_type'] = new \HTMLElement\HTMLSelectElement();
        $html_element['question_type']->setCaption('Тип вопроса');
        $html_element['question_type']->setConfig('class', 'uk-width-1-2');
        $html_element['question_type']->setName('question_type');
        $html_element['question_type']->setId('question_type');
        $html_element['question_type']->setNecessarily(true);

        $html_element['explanation'] = new \HTMLElement\HTMLTextTextareaElement();
        $html_element['explanation']->setName('explanation');
        $html_element['explanation']->setId('explanation');
        $html_element['explanation']->setValue($question['explanation']);
        $html_element['explanation']->setMax(1000);
        $html_element['explanation']->setCaption('Объяснение');
        $html_element['explanation']->setConfig('rows', '5');
        $html_element['explanation']->setConfig('class', 'uk-width-1-1');
        $html_element['explanation']->setConfig('placeholder', 'Объяснение');
        $html_element['explanation']->setValueFromRequest();

        $html_element['comment'] = new \HTMLElement\HTMLTextTextareaElement();
        $html_element['comment']->setName('comment');
        $html_element['comment']->setId('comment');
        $html_element['comment']->setValue($question['comment']);
        $html_element['comment']->setMax(1000);
        $html_element['comment']->setCaption('Комментарий');
        $html_element['comment']->setConfig('rows', '5');
        $html_element['comment']->setConfig('class', 'uk-width-1-1');
        $html_element['comment']->setConfig('placeholder', 'Комментарий');
        $html_element['comment']->setValueFromRequest();

        $time = $app_validate->setTimeArrayFromTime($question['question_time'], 'H:i:s');

        $html_element['hour'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['hour']->setName('hour');
        $html_element['hour']->setId('hour');
        $html_element['hour']->setValue(0);
        $html_element['hour']->setMax(3);
        $html_element['hour']->setCaption('часы');
        $html_element['hour']->setConfig('type', 'number');
        $html_element['hour']->setConfig('min', '0');
        $html_element['hour']->setConfig('max', '838');
        $html_element['hour']->setValue($time['hour']);
        $html_element['hour']->setConfig('class', 'uk-width-1-2');
        $html_element['hour']->setConfig('placeholder', 'чч');
        $html_element['hour']->setValueFromRequest();

        $html_element['minute'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['minute']->setName('minute');
        $html_element['minute']->setId('minute');
        $html_element['minute']->setValue(0);
        $html_element['minute']->setMax(2);
        $html_element['minute']->setCaption('минуты');
        $html_element['minute']->setConfig('type', 'number');
        $html_element['minute']->setConfig('min', '0');
        $html_element['minute']->setConfig('max', '59');
        $html_element['minute']->setValue($time['minute']);
        $html_element['minute']->setConfig('class', 'uk-width-1-2');
        $html_element['minute']->setConfig('placeholder', 'мм');
        $html_element['minute']->setValueFromRequest();

        $html_element['second'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['second']->setName('second');
        $html_element['second']->setId('second');
        $html_element['second']->setValue(0);
        $html_element['second']->setMax(2);
        $html_element['second']->setCaption('секунды');
        $html_element['second']->setConfig('type', 'number');
        $html_element['second']->setConfig('min', '0');
        $html_element['second']->setConfig('max', '59');
        $html_element['second']->setValue($time['second']);
        $html_element['second']->setConfig('class', 'uk-width-1-2');
        $html_element['second']->setConfig('placeholder', 'сс');
        $html_element['second']->setValueFromRequest();

        $option_question_time_flag_select = $question['question_time_flag'];
        $option_question_time_flag = [];
        $optgroup_question_time_flag = [];

        if (isset($_POST['question_time_flag']))
        {
            $option_question_time_flag_select = $_POST['question_time_flag'];
            $option_question_time_flag_select = intval($option_question_time_flag_select);
            if ($option_question_time_flag_select != APP_NO
                && $option_question_time_flag_select != APP_YES)
            {
                $option_question_time_flag_select = APP_NO;
            }
        }

        $i = 0;
        $option_question_time_flag[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_question_time_flag[$i]->setValue(APP_YES);
        $option_question_time_flag[$i]->setText('Да');
        ($option_question_time_flag_select == $option_question_time_flag[$i]->getValue())? $option_question_time_flag[$i]->setSelected(true):'';

        $i = 1;
        $option_question_time_flag[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_question_time_flag[$i]->setValue(APP_NO);
        $option_question_time_flag[$i]->setText('Нет');
        ($option_question_time_flag_select == $option_question_time_flag[$i]->getValue())? $option_question_time_flag[$i]->setSelected(true):'';

        $html_element['question_time_flag'] = new \HTMLElement\HTMLSelectElement();
        $html_element['question_time_flag']->setCaption('Включить время');
        $html_element['question_time_flag']->setConfig('class', 'uk-width-1-4');
        $html_element['question_time_flag']->setConfig('onchange', "show_hide('question_time_flag', 'q_time');");
        $html_element['question_time_flag']->setName('question_time_flag');
        $html_element['question_time_flag']->setId('question_time_flag');
        $html_element['question_time_flag']->setNecessarily(true);

        $option_flag_select = $question['flag'];
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
        $option_flag[$i]->setText('Вкл');
        ($option_flag_select == $option_flag[$i]->getValue())? $option_flag[$i]->setSelected(true):'';

        $i = 1;
        $option_flag[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_flag[$i]->setValue(FLAG_OFF);
        $option_flag[$i]->setText('Выкл');
        ($option_flag_select == $option_flag[$i]->getValue())? $option_flag[$i]->setSelected(true):'';

        $html_element['flag'] = new \HTMLElement\HTMLSelectElement();
        $html_element['flag']->setCaption('Состояние');
        $html_element['flag']->setConfig('class', 'uk-width-1-4');
        $html_element['flag']->setName('flag');
        $html_element['flag']->setId('flag');

        if ($question['flag'] == FLAG_NO_CHANGE)
        {
            $errors['no_change'] = 'Невозможно изменить данный вопрос';
            $html_element['name']->setConfig('disabled', 'disabled');
            $html_element['number']->setConfig('disabled', 'disabled');
            $html_element['question_type']->setConfig('disabled', 'disabled');
            $html_element['explanation']->setConfig('disabled', 'disabled');
            $html_element['comment']->setConfig('disabled', 'disabled');

            $html_element['hour']->setConfig('disabled', 'disabled');
            $html_element['minute']->setConfig('disabled', 'disabled');
            $html_element['second']->setConfig('disabled', 'disabled');
            $html_element['question_time_flag']->setConfig('disabled', 'disabled');
            $html_element['flag']->setConfig('disabled', 'disabled');
            $option_flag_select = FLAG_NO_CHANGE;
        }


        if (isset($_POST['p_i']))
        {
            $question['path_img'] = htmlspecialchars($_POST['p_i']);
        }

        $img_src = $src_2;
        $full_file_path = ROOT . '\\app\\templates\\images\\questions\\'.$question['path_img'];

        if (isset($_POST['edit']))
        {
            if ($qid != $question['id'])
            {
                $errors['id'] = 'Невозможно внести изменения для данного вопроса';
            }
            /**
             * Обработка изображения Begin
             */
            $user_dir = ROOT.'\\temp\\users\\'.$u_id;

            $types = array('image/gif' => '.gif', 'image/png' => '.png', 'image/jpeg' => '.jpg');

            $file_max_size = '3145728'; // 3Мб
            $file_max = 0;

            // Получаем максимальное значение файла из настроек php.ini
            $file_max_size_php_ini = ini_get('post_max_size');

            $file_max_size_php_ini = $this->parse_size($file_max_size_php_ini);
            if ((int)$file_max_size <= (int)$file_max_size_php_ini)
            {
                $file_max = $file_max_size;
            }
            else
            {
                $file_max = $file_max_size_php_ini;
            }

            $file_type = '';
            $file_name = '';

            if ($_FILES['path_img']['error'] == UPLOAD_ERR_OK)
            {
                $i = 1;
                foreach ($types as $key => $value)
                {
                    if ($key == $_FILES['path_img']['type'] && $_FILES['path_img']['size'] <= $file_max)
                    {
                        $file_type = $value;
                        $file_name = 'path_img'.$file_type;
                        break;
                    }
                    $i++;
                }
                if ($i == count($types)+1)
                {
                    $errors['path_img'] = 'Изображение должно быть формата .jpg, .png или .gif';
                }
            }
            if ($_FILES['path_img']['size'] > $file_max)
            {
                $errors['path_img'] = 'Изображение не должно превышать 3 Мб';
            }
            if ($file_name != '')
            {
                $full_file_path = $user_dir.'\\'.$file_name;
                move_uploaded_file($_FILES['path_img']['tmp_name'], $full_file_path);
                $question['path_img'] = $file_name;
                $is_new_file_name = true;
            }

            if ($is_new_file_name)
            {
                $img_src = $src_1;
            }

            /**
             * Обработка изображения End
             */

            if ($question['path_img'] == null)
            {
                $img_delete = true;
            }

            $html_element['name']->setValue($html_element['name']->getValue());
            $html_element['number']->setValue($html_element['number']->getValue());
            $html_element['explanation']->setValue($html_element['explanation']->getValue());
            $html_element['comment']->setValue($html_element['comment']->getValue());

            $html_element['name']->check();
            $html_element['number']->check();
            $html_element['explanation']->check();
            $html_element['comment']->check();

            if ($option_question_time_flag_select == APP_YES)
            {
                $html_element['hour']->setValue(trim(intval($html_element['hour']->getValue())));
                $html_element['minute']->setValue(trim(intval($html_element['minute']->getValue())));
                $html_element['second']->setValue(trim(intval($html_element['second']->getValue())));

                $html_element['hour']->check();
                $html_element['minute']->check();
                $html_element['second']->check();

                if ($html_element['hour']->getValue() == 0
                    && $html_element['minute']->getValue() == 0
                    && $html_element['second']->getValue() == 0)
                {
                    $errors['question_time'] = 'Вы включили время, но время для ответа на вопрос не задали.<br />Укажите часы или минуты, или секунды.';
                    $html_element['hour']->setCheck(false);
                    $html_element['minute']->setCheck(false);
                    $html_element['second']->setCheck(false);
                }
            }

            if (!$html_element['name']->getCheck())
            {
                $errors['name'] = 'Ошибка в поле "'.$html_element['name']->getCaption().'".<br />Не может быть такой длины.';
            }

            if (!$app_validate->checkInt($html_element['number']->getValue(), true, true, 0, 999999))
            {
                $html_element['number']->setCheck(false);
            }

            if (!$html_element['number']->getCheck())
            {
                $errors['number'] = 'Ошибка в поле "'.$html_element['number']->getCaption().'".<br />Должно быть целым числом от 0 до 999999.';
            }

            if (!$html_element['explanation']->getCheck())
            {
                $errors['explanation'] = 'Ошибка в поле "'.$html_element['explanation']->getCaption().'".<br />Не может быть такой длины.';
            }

            if (!$html_element['comment']->getCheck())
            {
                $errors['comment'] = 'Ошибка в поле "'.$html_element['comment']->getCaption().'".<br />Не может быть такой длины.';
            }

            if (!$app_validate->checkInt($html_element['hour']->getValue(), true, true, 0, 838))
            {
                $html_element['hour']->setCheck(false);
            }

            if (!$html_element['hour']->getCheck())
            {
                $errors['hour'] = 'Ошибка в поле "'.$html_element['hour']->getCaption().'".<br />Должно быть целым числом от 0 до 838.';
            }

            if (!$app_validate->checkInt($html_element['minute']->getValue(), true, true, 0, 59))
            {
                $html_element['minute']->setCheck(false);
            }

            if (!$html_element['minute']->getCheck())
            {
                $errors['minute'] = 'Ошибка в поле "'.$html_element['minute']->getCaption().'".<br />Должно быть целым числом от 0 до 59.';
            }

            if (!$app_validate->checkInt($html_element['second']->getValue(), true, true, 0, 59))
            {
                $html_element['second']->setCheck(false);
            }

            if (!$html_element['second']->getCheck())
            {
                $errors['second'] = 'Ошибка в поле "'.$html_element['second']->getCaption().'".<br />Должно быть целым числом от 0 до 59.';
            }

            if ($errors === false)
            {
                $time['hour'] = $html_element['hour']->getValue();
                $time['minute'] = $html_element['minute']->getValue();
                $time['second'] = $html_element['second']->getValue();
                $question['question_time'] = $app_validate->getTimeFromArrayInt($time);
                if (!$question['question_time'])
                {
                    $errors['question_time'] = 'Не удалось установить время';
                }

                if ($errors === false)
                {
                    $question['name'] = $html_element['name']->getValue();
                    $question['number'] = $html_element['number']->getValue();
                    $question['question_type_id'] = $option_question_type_selected;
                    $question['explanation'] = $html_element['explanation']->getValue();
                    $question['comment'] = $html_element['comment']->getValue();
                    $question['test_id'] = $search['test_id'];
                    $file_name = $question['path_img'];
                    $question['path_img'] = '';

                    $file_type = '';
                    $name = '';
                    if (!$is_new_file_name)
                    {
                        if (!$img_delete)
                        {
                            $name = $old_file;
                        }
                    }
                    // question_time значение передано выше
                    $question['question_time_flag'] = $option_question_time_flag_select;
                    $question['change_user_id'] = $u_id;
                    $question['change_datetime'] = $date_time->format('Y-m-d H:i:s');
                    $question['flag'] = $option_flag_select;

                    Question::edit($question);

                    $old_file_path = ROOT.'\\app\\templates\\images\\questions\\'.$old_file;
                    if ($img_delete)
                    {
                        if (file_exists($old_file_path))
                        {
                            unlink($old_file_path);
                        }
                        goto _gt_index;
                    }

                    if ($is_new_file_name)
                    {
                        $file = $user_dir.'\\'.$file_name;
                        if (file_exists($file))
                        {
                            if (exif_imagetype($file) == IMAGETYPE_GIF)
                            {
                                $file_type = '.gif';
                            }
                            if (exif_imagetype($file) == IMAGETYPE_JPEG)
                            {
                                $file_type = '.jpg';
                            }
                            if (exif_imagetype($file) == IMAGETYPE_PNG)
                            {
                                $file_type = '.png';
                            }
                            $name = $question['id'].$file_type;
                            copy($user_dir.'\\'.$file_name, ROOT.'\\app\\templates\\images\\questions\\'.$name);
                            foreach ($types as $key => $value)
                            {
                                if (file_exists($user_dir.'\\path_img'.$value))
                                {
                                    unlink($user_dir.'\\path_img'.$value);
                                }
                            }
                        }
                    }

                    _gt_index:
                    Question::updatePathImg($question['id'], $name);
                    header('Location: /question/index?'.$url_param);
                }
            }

        }
        $img_src .= $question['path_img'];

        if ($is_can)
        {
            include_once APP_VIEWS.'question/edit.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }

    public function actionDelete()
    {
        $user_right = parent::getUserRight();
        $app_validate = new App_Validate();
        $url_param = '';
        $is_can = false;
        $search = [];
        $page = 1;
        $errors = false;
        $date_time = new DateTime();
        $question = [];
        $qid = null;

        foreach ($user_right as $u_r)
        {
            if ($u_r['right_name'] == CAN_MODERATOR_QUESTION)
            {
                $is_can = true;
                break;
            }
        }

        if (isset($_GET['s_direction']))
        {
            $search['direction_id'] = htmlspecialchars($_GET['s_direction']);
        }
        if (isset($_GET['tid']))
        {
            $search['test_id'] = htmlspecialchars($_GET['tid']);
        }
        if (isset($_GET['s_name']))
        {
            $search['test_name'] = htmlspecialchars($_GET['s_name']);
        }
        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }

        if ($page < 1)
        {
            $page = 1;
        }

        if (isset($_GET['s_q_name']))
        {
            $search['name'] = htmlspecialchars($_GET['s_q_name']);
        }

        if (isset($_GET['qid']))
        {
            $qid = htmlspecialchars($_GET['qid']);
        }

        $url_param .= 's_direction='.$search['direction_id'].'&s_name='.$search['test_name']
            .'&tid='.$search['test_id'].'&page='.$page.'&s_q_name='.$search['name'];

        $question = Question::getQuestion($qid);

        if ($question['flag'] == FLAG_NO_CHANGE)
        {
            $errors['no_change'] = 'Невозможно изменить данный вопрос';
        }

        if (isset($_POST['yes']))
        {
            if ($qid != $question['id'])
            {
                $errors['id'] = 'Невозможно внести изменения для данного вопроса';
            }
            if ($errors === false)
            {
                $question['change_user_id'] = User::checkLogged();
                $question['change_datetime'] = $date_time->format('Y-m-d H:i:s');
                Question::delete($question);

                header('Location: /question/index?'.$url_param);
            }
        }

        if (isset($_POST['no']))
        {
            header('Location: /question/index?'.$url_param);
        }

        if ($is_can)
        {
            include_once APP_VIEWS.'question/delete.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }

    private function parse_size($value) {
        static $sizes = ['K' => 1024, 'M' => 1048576, 'G' => 1073741824];
        $last = substr($value, -1);
        return isset($sizes[$last]) ? (int)$value * $sizes[$last] : $value;
    }
}