<?php
$pagetitle = 'Результат';
$page_id = 'page_result';

//Подключаем шапку
include APP_VIEWS . 'layouts/header.php';
?>

    <h1><?= $pagetitle ?></h1>
    <a class="back" href="/result/index?<?= $url_param ?>">&larr; Вернуться назад</a>

    <div data-uk-grid class="uk-width-2-3 uk-margin-large-top uk-align-center">
        <?php
        if (is_array($errors) && count($errors) > 0):
            foreach ($errors as $error):
                echo App_Message::getMessage($error, MESSAGE_TYPE_ERROR);
            endforeach; // foreach ($errors as $error):
        endif; //if (is_array($errors) && count($errors) > 0):
        ?>

        <?php if (!isset($errors['no_testing_result']) || $errors['no_testing_result'] == null): ?>
        <div style="margin-top: -40px" class="uk-button fr"
             onclick="window.open('/result/print?testing_result_id=<?= $search['testing_result_id'] ?>&user_id=<?= $search['user_id'] ?>', 'new', 'width=1000,height=800,top=50,left=50')">
            <i class="uk-icon-print"></i> Распечатать
        </div>

        <table class="uk-form-row uk-width-1-1 result" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <table class="uk-form-row uk-width-1-1" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>
                                <div class="extra">Участник:</div>
                                <?= trim($testing_result_info['lastname'] . ' '. $testing_result_info['firstname'] .' '
                                    . $testing_result_info['middlename']) ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="extra">Направление:</div><?= trim($testing_result_info['direction_name']) ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="extra">Тестирование:</div><?= trim($testing_result_info['testing_name']) ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="extra">Дата:</div> <?= $end_testing_date ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="extra">Количество вопросов:</div><?= trim($testing_result_info['question_count']) ?>
                            </td>
                        </tr>
                    </table>
                    <table class="uk-form-row uk-width-1-1" cellspacing="0" cellpadding="0" style="margin-top: -5px">
                        <tr>
                            <td class="uk-width-1-2">
                                <div class="extra">Результат:</div>
                                <?php
                                if ($is_testing_complete)
                                {
                                    echo '<b style="color: #659f13;">Тест пройден</b>';
                                }
                                else
                                {
                                    echo '<b style="color: #d85030;">Тест не пройден</b>';
                                }
                                ?>
                            </td>
                            <td class="uk-width-1-2">
                                <div class="extra">Время:</div><?= $total_question_time ?>
                            </td>
                        </tr>
                    </table>
                    <table class="uk-form-row uk-width-1-1" cellspacing="0" cellpadding="0" style="margin-top: -5px">
                        <tr>
                            <td class="uk-width-1-3 uk-alert <?php echo ($is_testing_complete)?  'uk-alert-success' : 'uk-alert-danger' ?>">
                                Набрано баллов: <b><?= $points_scored ?></b>
                            </td>
                            <td class="uk-width-1-3 uk-alert">
                                Балл для прохождения: <b><?= $points_min ?></b>
                            </td>
                            <td class="uk-width-1-3 uk-alert">
                                Максимльное кол-ов баллов: <b><?= $points_max ?></b>
                            </td>

                        </tr>
                    </table>
                    <table class="uk-form-row uk-width-1-1" cellspacing="0" style="margin-top: -5px">
                        <tr>
                            <td class="uk-width-1-3 uk-alert uk-alert-success">
                                Правильные ответы: <b><?= $count_correct ?></b>
                            </td>
                            <td class="uk-width-1-3 uk-alert uk-alert-danger">
                                Неправильные ответы: <b><?= $count_wrong ?></b>
                            </td>
                            <td class="uk-width-1-3 uk-alert">
                                Пропущенные вопросы: <b><?= $count_scip ?></b>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <?php if (is_array($filtered_result_report) && count($filtered_result_report) > 0): ?>
                <tr><td style="border: 2px solid #808080"></td></tr>
                <?php
                $q_number = 0;
                foreach ($filtered_result_report as $frr_question_id => $frr_value):
                    $q_number++;
                ?>
                <tr>
                    <td>
                        <table class="uk-form-row uk-width-1-1" cellspacing="0" cellpadding="0" style="margin-top: -5px">
                            <tr>
                                <?php
                                if (in_array($frr_question_id, $scip_questions)):
                                ?>
                                <td class="uk-width-1-1 uk-alert">
                                <?php
                                elseif (array_key_exists($frr_question_id, $wrong_answers)):
                                ?>
                                <td class="uk-width-1-1 uk-alert uk-alert-danger">
                                <?php
                                else:
                                ?>
                                <td class="uk-width-1-1 uk-alert uk-alert-success">
                                <?php
                                endif;
                                ?>
                                    <?=$q_number.'. '. $frr_value['question_name'] ?>
                                    <?php
                                    if ($frr_value['question_path_img'] != null):
                                        $img_src = 'http://quiz-v2/app/templates/images/questions/'.$frr_value['question_path_img'];
                                    ?>
                                    <div align="center">
                                        <img src="<?= $img_src ?>" alt="Изображение отсутствует" class="quiz_img" />
                                    </div>
                                    <?php
                                    endif; //if ($frr_value['question_path_img'] != null):
                                    ?>
                                    <?php if ($frr_value['question_explanation'] != null): ?>
                                    <div class="uk-comment-meta">
                                        <?= $frr_value['question_explanation'] ?>
                                    </div>
                                    <?php endif; // if ($frr_value['question_explanation'] != null): ?>
                                </td>
                            </tr>
                            <?php
                            if ($is_can_view_correct_answer):
                            ?>
                            <tr>
                                <td>

                                <?php
                                if (!in_array($frr_question_id, $scip_questions)):
                                    if (array_key_exists($frr_question_id, $wrong_answers) ||
                                        count($frr_value['view_answers']['answered']) != count(($frr_value['view_answers']['right']))):
                                    ?>
                                    <div style="padding-left: 15px; padding-right: 15px;" class="uk-alert uk-alert-danger">
                                    <?php
                                    foreach ($frr_value['view_answers']['answered'] as $frr_vaa_answer_id => $frr_vaa_answer_value):
                                        if (in_array($frr_vaa_answer_id, $wrong_answers[$frr_question_id])):
                                        ?>
                                        <i class="uk-icon-times"></i><?= $frr_vaa_answer_value['name'] ?><br />
                                        <?php
                                        else:
                                        ?>
                                        <i class="uk-icon-check"></i><?= $frr_vaa_answer_value['name'] ?><br />
                                        <?php
                                        endif;
                                    ?>


                                <?php
                                    endforeach; // foreach ($frr_value['view_answers']['answered'] as $frr_vaa_answer_id => $frr_vaa_answer_value):
                                ?>
                                    </div>
                                    <div style="padding-left: 15px; padding-right: 15px;" class="uk-alert uk-alert-success">
                                <?php
                                    foreach ($frr_value['view_answers']['right'] as $frr_var_key => $frr_var_value):
                                    ?>
                                        <i class="uk-icon-check"></i><?= $frr_var_value['name'] ?><br />
                                    <?php
                                    endforeach; //foreach ($frr_value['view_answers']['right'] as $frr_var_key => $frr_var_value):
                                ?>
                                    </div>
                                <?php
                                else:// if (array_key_exists($frr_question_id, $wrong_answers)):
                                    ?>
                                <div style="padding-left: 15px; padding-right: 15px;" class="uk-alert uk-alert-success">
                                    <?php
                                    foreach ($frr_value['view_answers']['answered'] as $frr_vaa_answer_id => $frr_vaa_answer_value):
                                    ?>
                                    <i class="uk-icon-check"></i><?= $frr_vaa_answer_value['name'] ?><br />
                                    <?php
                                    endforeach; //foreach ($frr_value['view_answers']['answered'] as $frr_vaa_answer_id => $frr_vaa_answer_value):
                                    ?>
                                </div>
                                <?php
                                    endif; // if (array_key_exists($frr_question_id, $wrong_answers) || count($frr_value['view_answers']['answered']) != count(($frr_value['view_answers']['right']))):
                                else: //if (!in_array($frr_question_id, $scip_questions)):
                                ?>
                                <div style="padding-left: 15px; padding-right: 15px;" class="uk-alert uk-alert-success">
                                    <?php
                                    foreach ($frr_value['view_answers']['right'] as $frr_var_key => $frr_var_value):
                                        ?>
                                        <i class="uk-icon-check"></i><?= $frr_var_value['name'] ?><br />
                                        <?php
                                    endforeach; //foreach ($frr_value['view_answers']['right'] as $frr_var_key => $frr_var_value):
                                    ?>
                                </div>
                                <?php
                                endif; //if (!in_array($frr_question_id, $scip_questions)):
                                ?>
                                </td>
                            </tr>
                            <?php
                            endif; //if ($is_can_view_correct_answer):
                            ?>
                        </table>
                    </td>
                </tr>
                <?php
                endforeach; // foreach ($filtered_result_report as $frr_question_id => $frr_value):
                ?>
            <?php endif; // if (is_array($filtered_result_report) && count($filtered_result_report) > 0): ?>
        </table>

        <?php endif; //if (!isset($errors['no_testing_result']) || $errors['no_testing_result'] == null): ?>

    </div>

<?php include APP_VIEWS . 'layouts/footer.php'; ?>