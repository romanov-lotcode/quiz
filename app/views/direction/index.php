<?php
$pagetitle = 'Направление';
$page_id = 'page_moderator';


//Подключаем шапку
include APP_VIEWS . 'layouts/header.php';
?>

        <h1><?= $pagetitle ?></h1>
        <div class="uk-width-8-10" align="left">
            <table class="view">
                <caption align="bottom"><?= $total_direction; ?></caption>
                <tr>
                    <th>№</th>
                    <th>Название</th>
                    <th>Состояние</th>
                </tr>

                <tr class="srow">
                    <td>1</td>
                    <td>Какое-то направление</td>
                    <td>Включен</td>
                </tr>
                <tr class="srow">
                    <td>2</td>
                    <td>Какое-то направление</td>
                    <td>Выключен</td>
                </tr>

            </table>

        </div>

        <div class="uk-width-1-2"><?php print_r($directions) ?></div>
        <div class="uk-width-1-2"><?= $total_direction; ?></div>



<?php include APP_VIEWS . 'layouts/footer.php'; ?>