<?php
$pagetitle = 'Направление';
$page_id = 'page_moderator';


//Подключаем шапку
include APP_VIEWS . 'layouts/header.php';
?>

        <h1><?= $pagetitle ?></h1>
        <div class="uk-width-8-10" align="left">
            <table class="uk-width-1-1 view">

                <tr>
                    <th class="uk-width-1-10">№</th>
                    <th class="uk-width-6-10">Название</th>
                    <th class="uk-width-1-10">Состояние</th>
                    <th class="uk-width-2-10">Действие</th>
                </tr>

                <tr class="srow">
                    <td>1</td>
                    <td>Какое-то направление</td>
                    <td>Включен</td>
                    <td></td>
                </tr>
                <tr class="srow">
                    <td>2</td>
                    <td>Какое-то направление</td>
                    <td>Выключен</td>
                    <td></td>
                </tr>

                <?php
                include APP_VIEWS . 'layouts/record_count.php';
                echo recordCount($total_direction, 2);
                ?>

                <!-- <caption align="bottom"><?//= $total_direction; ?></caption> -->

            </table>

        </div>

        <div class="uk-width-1-2"><?php print_r($directions) ?></div>
        <div class="uk-width-1-2"><?= $total_direction; ?></div>



<?php include APP_VIEWS . 'layouts/footer.php'; ?>