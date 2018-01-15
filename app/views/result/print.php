<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Результат</title>
    <style>
        html, body{
            margin: 0px;
            padding: 0px;
            width: 100%;
            height: 100%;
            color: #101921;
            font: normal 18px 'PT Sans', Calibri, Raleway, Lato, Arial, Helvetica, Tahoma, Verdana, Sans-Serif;
        }

        div.container{
            border: 1px solid #485557;
            margin: 10px;
            padding: 10px;
            width: 400px;
        }

        span.name{
            color: #485557;
            margin-right: 10px;
        }
    </style>
</head>
<body onload="print();">
<h3>Результат</h3>
<?php if (!isset($errors['no_testing_result']) || $errors['no_testing_result'] == null): ?>
    <div class="container">
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <span class="name">Участник:</span><?= trim($testing_result_info['lastname'] . ' '. $testing_result_info['firstname'] .' '
                        . $testing_result_info['middlename']) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="name">Направление:</span><?= trim($testing_result_info['direction_name']) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="name">Тестирование:</span><?= trim($testing_result_info['testing_name']) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="name">Дата:</span><?= $end_testing_date ?>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="name">Количество вопросов:</span><?= trim($testing_result_info['question_count']) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="extra">Результат:</span>
                    <b>Тест
                    <?php
                    echo ($is_testing_complete)? ' пройден': ' не пройден';
                    ?>
                    </b>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="extra">Время:</span><?= $total_question_time ?>
                </td>
            </tr>
            <tr>
                <td>
                    Набрано баллов: <b><?= $points_scored ?></b>
                </td>
            </tr>
            <tr>
                <td>
                    Балл для прохождения: <b><?= $points_min ?></b>
                </td>
            </tr>
            <tr>
                <td>
                    Максимльное кол-ов баллов: <b><?= $points_max ?></b>
                </td>
            </tr>
            <tr>
                <td>
                    Правильные ответы: <b><?= $count_correct ?></b>
                </td>
            </tr>
            <tr>
                <td>
                    Неправильные ответы: <b><?= $count_wrong ?></b>
                </td>
            </tr>
            <tr>
                <td>
                    Пропущенные вопросы: <b><?= $count_scip ?></b>
                </td>
            </tr>
        </table>
    </div>
<?php else: //if (!isset($errors['no_testing_result']) || $errors['no_testing_result'] == null): ?>
    <p><?= $errors['no_testing_result'] ?> </p>
<?php endif; //if (!isset($errors['no_testing_result']) || $errors['no_testing_result'] == null): ?>
</body>
</html>