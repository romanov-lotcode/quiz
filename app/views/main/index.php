<?php
$pagetitle = 'Пройти тестирование';
$page_id = 'page_index';


//Подключаем шапку
include APP_VIEWS . 'layouts/header.php';
?>

    <h1><?= $pagetitle ?></h1>
    <div class="uk-width-8-10" align="left">
    <?php if ($is_can): ?>
        <form method="GET" class="uk-form simple">
            <table class="uk-width-1-1 search_param">
                <tr>
                    <td class="uk-width-2-3" colspan="2">
                        <?= $html_element['direction']->render($option_direction, $optgroup_direction) ?>
                    </td>
                    <td class="uk-width-1-3">
                        <?php
                        if ($option_direction_selected == 0):
                            ?>
                            <button class="uk-button">Поиск</button>
                            <?php
                        endif; //if ($option_direction_selected == 0):
                        ?>
                    </td>
                </tr>
                <?php
                if ($option_direction_selected > 0):
                    ?>
                    <tr>
                        <td class="uk-width-2-3"  colspan="2">
                            <?= $html_element['name']->render() ?>
                        </td>
                        <td class="uk-width-1-3">
                            <button class="uk-button">Поиск</button>
                        </td>
                    </tr>
                    <?php
                endif; // if ($option_direction_selected > 0):
                ?>
            </table>
        </form>

        <?php
        if ($option_direction_selected > 0):
            ?>
            <form method="POST">
            <table class="uk-width-1-1 view">
                <tr>
                    <th class="uk-width-1-10" title="Порядковый номер">№</th>
                    <th class="uk-width-5-10" title="Название тестирования">Название</th>
                    <th class="uk-width-1-10" title="Время прохождения">Время (чч:мм:сс)</th>
                    <th class="uk-width-1-10" title="Балл прохождения">Балл</th>
                    <th class="uk-width-1-10" title="Количество вопросов">Вопросы</th>
                    <th class="uk-width-1-10">Действие</th>
                </tr>

                <?php
                $i=0;
                if (is_array($testing_list) && count($testing_list) > 0):
                    foreach ($testing_list as $t_item):
                        $index_number++;
                        $i++;
                        ?>

                        <tr class="srow">
                            <td><?= $index_number ?></td>
                            <td>
                                <?= $t_item['testing_name'] ?>
                                <br />
                                <div class="uk-comment-meta" title="Группа">Группа: <?= $t_item['user_group_name'] ?></div>
                            </td>
                            <td>
                                <?php
                                $time_value = '-';
                                if ($t_item['testing_time_flag'] == STATE_ON)
                                {
                                    $time_value = $t_item['testing_time'];
                                }
                                ?>
                                <?= $time_value ?>
                            </td>
                            <td><?= $t_item['minimum_score'] ?></td>
                            <td><?= $t_item['question_count'] ?></td>
                            <td>
                                <?php
                                $testing_count = 0;
                                if (is_array($testing_results) && count($testing_results) > 0)
                                {

                                    foreach ($testing_results as $tr_item)
                                    {
                                        if ($tr_item['testing_id'] == $t_item['testing_id']
                                            && $tr_item['user_group_id'] == $t_item['user_group_id'])
                                        {
                                            $testing_count = $tr_item['count'];
                                            break;
                                        }
                                    }
                                }
                                if ($t_item['testing_count'] > $testing_count):
                                ?>
                                    <button name="begin" value="<?= $t_item['id'] ?>" class="action" title="Начать тестирование"><span class="uk-icon-play"></span></button>
                                <?php
                                else:
                                    echo 'Тест пройден';
                                endif; // if ($t_item['testing_count'] > $testing_count):
                                ?>

                            </td>
                        </tr>
                        <?php
                    endforeach; //foreach ($testing_list as $t_item):
                endif; //if (is_array($testing_list) && count($testing_list) > 0):
                ?>

                <?php
                include APP_VIEWS . 'layouts/record_count.php';
                echo recordCount($total, $i);
                ?>

            </table>
            </form>

            <?= $pagination->get() ?>

            <?php
        endif; // if ($option_direction_selected > 0):
        ?>
    <?php else: //if ($is_can): ?>
        <h3>Вам не доступно прохождение тестирований</h3>
    <?php endif; //if ($is_can): ?>

    </div>
    <script src="<?= APP_TEMPLATES ?>css/chosen/chosen.jquery.js" type="text/javascript"></script>
    <script type="text/javascript">
        $("#s_direction").chosen({no_results_text: "Пока нет направлений", search_contains: true});
    </script>


<?php include APP_VIEWS . 'layouts/footer.php'; ?>