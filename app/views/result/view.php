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
                                <div class="extra">Время:</div>15 минут 15 сек
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
            <tr><td style="border: 2px solid #808080"></td></tr>
            <tr>
                <td>
                    <table class="uk-form-row uk-width-1-1" cellspacing="0" cellpadding="0" style="margin-top: -5px">
                        <tr>
                            <td class="uk-width-1-1">
                                Проверка
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <?php endif; //if (!isset($errors['no_testing_result']) || $errors['no_testing_result'] == null): ?>

    </div>

<?php include APP_VIEWS . 'layouts/footer.php'; ?>