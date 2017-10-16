<?php
$pagetitle = 'Тестирование';
$page_id = 'page_moderator';


//Подключаем шапку
include APP_VIEWS . 'layouts/header.php';
?>

    <h1><?= $pagetitle ?></h1>
    <?php if ($is_test_can): ?>
    <a class="back" href="/test/index?<?= $url_test_param ?>">&larr; Перейти к тесту</a>
    <?php endif; //if ($is_test_can): ?>
    <div class="uk-width-8-10" align="left">

        <form method="GET" class="uk-form simple">
            <input type="hidden" name="s_direction" value="<?= $search['direction_id'] ?>" />
            <input type="hidden" name="s_test" value="<?= $search['test_id'] ?>" />
            <input type="hidden" name="s_test_name" value="<?= $search['s_test_name'] ?>" />
            <input type="hidden" name="test_page" value="<?= $search['test_page'] ?>" />
            <input type="hidden" name="page" value="<?= $page ?>" />
            <table class="uk-width-1-1 search_param">
                <?php
                if ($test['name'] != null):
                ?>
                <tr>
                    <td colspan="3">
                        Тест: "<?= $test['name'] ?>"
                    </td>
                </tr>
                <?php
                endif; //if ($test['name'] != null):
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
            </table>
        </form>


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
                            <?= $testingTime.' '.$app_state->getTimeFlagState($t_item['testing_time_flag']) ?>
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
    </div>
    <script src="<?= APP_TEMPLATES ?>css/chosen/chosen.jquery.js" type="text/javascript"></script>
    <script type="text/javascript">
        $("#s_direction").chosen({no_results_text: "Пока нет направлений", search_contains: true});
        $("#s_test").chosen({no_results_text: "Пока нет тестов", search_contains: true});
    </script>

<?php include APP_VIEWS . 'layouts/footer.php'; ?>