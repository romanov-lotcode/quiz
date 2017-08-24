<?php
$pagetitle = 'Вопрос';
$page_id = 'page_moderator';

//Подключаем шапку
include APP_VIEWS . 'layouts/header.php';
?>
    <script src="<?= APP_TEMPLATES ?>js/jquery-1.8.min.js"></script>
    <script src="<?= APP_TEMPLATES ?>js/javascript.js"></script>
    <h1><?= $pagetitle ?></h1>
    <a class="back" href="/question/index?<?= $url_param ?>">&larr; Вернуться назад</a>

    <div data-uk-grid class="uk-width-1-2 uk-margin-large-top uk-align-center">
        <?php
        if (is_array($errors) && count($errors) > 0):
            foreach ($errors as $error):
                echo App_Message::getMessage($error, MESSAGE_TYPE_ERROR);
            endforeach; // foreach ($errors as $error):
        endif; //if (is_array($errors) && count($errors) > 0):
        ?>

        <form method="POST" class="uk-form" enctype="multipart/form-data" id="frm">
            <div class="uk-form-row">
                <legend class="app">Редактировать</legend>
            </div>
            <div class="uk-form-row uk-width-1-1">
                <?php if($question['test_name'] != null): ?>
                    Тест: "<?= $question['test_name'] ?>"
                <?php endif; //if($question['test_name'] != null): ?>
            </div>
            <div class="uk-form-row uk-width-1-1">
                <?= $html_element['name']->render(); ?>
            </div>
            <div class="uk-form-row uk-width-1-1">
                <!-- Область для перетаскивания -->

                <?php
                $img_show = 0;

                if ($question['path_img'] != null && file_exists($full_file_path))
                {
                    $img_show = 1;
                }
                ?>

                <?php if ($question['flag'] != FLAG_NO_CHANGE): ?>
                <div id="drop-files" ondragover="return false" class="uk-form-file" <?php if ($img_show == 1) echo 'style="display:none;"' ?>>
                    Выберите или перетащите изображение сюда
                    <input type="hidden" name="p_i" value="<?= $question['path_img'] ?>" id="p_i" />
                    <input type="file" id="uploadbtn" name="path_img" />
                </div>
                <?php endif; //if ($question['flag'] != FLAG_NO_CHANGE): ?>

                <div id="img_path_img" <?php if ($img_show == 0) echo 'style="display:none;"' ?>>
                    <span>Был выбран 1 файл</span>
                    <a href="#" class="uk-button delete">Удалить</a><br /><br />
                    <img src="<?= $img_src ?>" class="image">
                </div>

                <!-- Область предпросмотра -->
                <div id="uploaded-holder">
                    <div id="dropped-files">
                        <!-- Кнопки загрузить и удалить, а также количество файлов -->
                        <div id="upload-button">
                            <span>0 Файлов</span>
                            <a href="#" class="uk-button delete">Удалить</a>
                            <!-- Прогресс бар загрузки -->
                            <div id="loading">
                                <div id="loading-bar">
                                    <div class="loading-color"></div>
                                </div>
                                <div id="loading-content"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Список загруженных файлов -->
                <div id="file-name-holder">
                    <ul id="uploaded-files">

                    </ul>
                </div>
            </div>
            <div class="uk-form-row uk-width-1-1">
                <?= $html_element['number']->render(); ?>
            </div>
            <div class="uk-form-row uk-width-1-1">
                <?= $html_element['question_type']->render($option_question_type, $optgroup_question_type); ?>
            </div>
            <div class="uk-form-row uk-width-1-1">
                <?= $html_element['explanation']->render(); ?>
            </div>
            <div class="uk-form-row uk-width-1-1">
                <?= $html_element['comment']->render(); ?>
            </div>
            <div class="uk-form-row uk-width-1-1">
                <?= $html_element['question_time_flag']->render($option_question_time_flag, $optgroup_question_time_flag); ?>
            </div>
            <div class="uk-form-row uk-width-1-1" id="q_time"
                <?php
                if ($option_question_time_flag_select == APP_NO)
                    echo ' style="display: none;" ';
                ?>
                >
                <table class="uk-width-1-1 search_param">
                    <tr>
                        <td align="center" colspan="3"><label>Время для ответа на вопрос</label></td>
                    </tr>
                    <tr>
                        <td class="uk-width-1-3">
                            <?= $html_element['hour']->render(); ?>
                        </td>
                        <td class="uk-width-1-3">
                            <?= $html_element['minute']->render(); ?>
                        </td>
                        <td class="uk-width-1-3">
                            <?= $html_element['second']->render(); ?>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="uk-form-row uk-width-1-1">
                <?= $html_element['flag']->render($option_flag, $optgroup_flag) ?>
            </div>
            <?php if ($question['flag'] != FLAG_NO_CHANGE): ?>
            <div class="uk-form-row uk-width-1-1">
                <button class="uk-button" name="edit">Редактировать</button>
            </div>
            <?php endif;//if ($question['flag'] != FLAG_NO_CHANGE): ?>

        </form>
    </div>

    <script type='text/javascript'>
        function show_hide(id_changing_element, id_changed_element)
        {
            if (document.getElementById(id_changing_element).value == 1)
            {
                document.getElementById(id_changed_element).style.display = 'block'; //покажет
            }
            else
            {
                document.getElementById(id_changed_element).style.display='none'; // Скроет слой
            }
        }
    </script>



<?php include APP_VIEWS . 'layouts/footer.php'; ?>