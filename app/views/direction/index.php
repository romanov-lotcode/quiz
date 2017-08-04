<?php
$pagetitle = 'Направление';
$page_id = 'page_moderator';


//Подключаем шапку
include APP_VIEWS . 'layouts/header.php';
?>

        <h1><?= $pagetitle ?></h1>
        <div class="uk-width-8-10" align="left">

            <form method="POST" class="uk-form simple">
                <table class="uk-width-1-1 search_param">
                    <tr>
                        <td class="uk-width-1-3">
                            <?= $html_element['name']->render() ?>
                        </td>
                        <td class="uk-width-1-3">
                            <button class="uk-button" name="registration">Поиск</button>
                        </td>
                        <td class="uk-width-1-3"></td>
                    </tr>
                </table>
            </form>

            <table class="uk-width-1-1 view">
                <tr>
                    <th class="uk-width-1-10">№</th>
                    <th class="uk-width-6-10">Название</th>
                    <th class="uk-width-1-10">Состояние</th>
                    <th class="uk-width-2-10">Действие</th>
                </tr>

                <?php
                if (is_array($directions) && count($directions) > 0):
                    $i=0;
                    foreach ($directions as $d_item):
                        $index_number++;
                        $i++;
                ?>

                        <tr class="srow">
                            <td><?= $index_number ?></td>
                            <td><?= $d_item['name'] ?></td>
                            <td><?= $d_item['flag'] ?></td>
                            <td>Кнопки</td>
                        </tr>
                <?php
                    endforeach; //foreach ($directions as $d_item):
                endif; //if (is_array($directions) && count($directions) > 0):
                ?>

                <?php
                include APP_VIEWS . 'layouts/record_count.php';
                echo recordCount($total_direction, $i);
                ?>

                <!-- <caption align="bottom"><?//= $total_direction; ?></caption> -->

            </table>

            <div class="uk-width-1-1"><?php print_r($directions) ?></div>
        </div>






<?php include APP_VIEWS . 'layouts/footer.php'; ?>