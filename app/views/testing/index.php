<?php
$pagetitle = 'Тестирование';
$page_id = 'page_moderator';


//Подключаем шапку
include APP_VIEWS . 'layouts/header.php';
?>

    <h1><?= $pagetitle ?></h1>
    <div class="uk-width-8-10" align="left">

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
                        <td class="uk-width-2-3" colspan="2">
                            <?= $html_element['test']->render($option_test, $optgroup_test) ?>
                        </td>
                        <td class="uk-width-1-3">
                            <?php
                            if ($option_test_selected == 0):
                                ?>
                                <button class="uk-button">Поиск</button>
                                <?php
                            endif; //if ($option_test_selected == 0):
                            ?>
                        </td>
                    </tr>

                    <?php
                    if ($option_test_selected > 0):
                    ?>
                        <tr>
                            <td class="uk-width-2-3"  colspan="2">
                                <?= $html_element['name']->render() ?>
                            </td>
                            <td class="uk-width-1-3">
                                <button class="uk-button">Поиск</button>
                                <a href="/testing/add?<?= $url_param ?>" class="uk-button fr" title="Добавить">Добавить</a>
                            </td>
                        </tr>

                <?php
                    endif; //if ($option_test_selected > 0):
                endif; // if ($option_direction_selected > 0):
                ?>
            </table>
        </form>

        <?php
        if ($option_direction_selected > 0 && $option_test_selected > 0):
            ?>
            <table class="uk-width-1-1 view">
                <tr>
                    <th class="uk-width-1-10">№</th>
                    <th class="uk-width-4-10" title="Название тестирования">Название</th>
                    <th class="uk-width-1-10" title="Балл для прохождения">Балл</th>
                    <th class="uk-width-1-10" title="Количество вопросов">Вопросы</th>
                    <th class="uk-width-1-10" title="Время прохождения">Время</th>
                    <th class="uk-width-1-10">Состояние</th>
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
                            <td><?= $t_item['name'] ?></td>
                            <td><?= $t_item['minimum_score'] ?></td>
                            <td><?= $t_item['question_count'] ?></td>
                            <?php
                            if(isset($t_item['testing_time']))
                            {
                                $timeSegments = explode(':', $t_item['testing_time']);
                                if(((integer)$timeSegments[0] > 0 || (integer)$timeSegments[1] > 0 || (integer)$timeSegments[2] > 0))
                                {
                                    $testingTime =  $t_item['testing_time'];
                                }
                                else
                                {
                                    $testingTime =  "-";
                                }
                            }
                            ?>
                            <td>
                                <?= $testingTime ?>
                            </td>

                            <td><?= $app_state->getFlagState($t_item['flag']) ?></td>
                            <td>
                                <?php
                                if ($t_item['flag'] != FLAG_NO_CHANGE):
                                    ?>
                                    <a href="/testing/edit?<?= $url_param . '&tid='.$t_item['id'] ?>" class="action" title="Редактировать"><span class="uk-icon-pencil"></span></a>
                                    <a href="/testing/delete?<?= $url_param . '&tid='.$t_item['id'] ?>" class="action" title="Удалить"><span class="uk-icon-trash"></span></a>
                                    <?php
                                endif; //if ($t_item['flag'] != FLAG_NO_CHANGE):
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

            <?= $pagination->get() ?>

            <?php
        endif; // if ($option_direction_selected > 0):
        ?>
    </div>
    <script src="<?= APP_TEMPLATES ?>css/chosen/chosen.jquery.js" type="text/javascript"></script>
    <script type="text/javascript">
        $("#s_direction").chosen({no_results_text: "Пока нет направлений", search_contains: true});
        $("#s_test").chosen({no_results_text: "Пока нет тестов", search_contains: true});
    </script>

<?php include APP_VIEWS . 'layouts/footer.php'; ?>