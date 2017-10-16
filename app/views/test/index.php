<?php
$pagetitle = 'Тест';
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
                        <a href="/test/add?<?= $url_param ?>" class="uk-button fr" title="Добавить">Добавить</a>
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
                        <a href="/test/add?<?= $url_param ?>" class="uk-button fr" title="Добавить">Добавить</a>
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
        <table class="uk-width-1-1 view">
            <tr>
                <th class="uk-width-1-10">№</th>
                <th class="uk-width-6-10">Название</th>
                <th class="uk-width-1-10">Состояние</th>
                <th class="uk-width-2-10">Действие</th>
            </tr>

            <?php
            $i=0;
            if (is_array($tests) && count($tests) > 0):
                foreach ($tests as $t_item):
                    $index_number++;
                    $i++;
                    ?>

                    <tr class="srow">
                        <td><?= $index_number ?></td>
                        <td><?= $t_item['name'] ?></td>
                        <td><?= $app_state->getFlagState($t_item['flag']) ?></td>
                        <td>
                            <?php
                            if ($t_item['flag'] != FLAG_NO_CHANGE):
                                ?>
                                <a href="/test/edit?<?= $url_param . '&tid='.$t_item['id'] ?>" class="action" title="Редактировать"><span class="uk-icon-pencil"></span></a>
                                <a href="/test/delete?<?= $url_param . '&tid='.$t_item['id'] ?>" class="action" title="Удалить"><span class="uk-icon-trash"></span></a>
                            <?php
                            endif; //if ($t_item['flag'] != FLAG_NO_CHANGE):
                            if ($is_can_question):
                            ?>
                                <a href="/question/index?<?= $url_param .'&tid='.$t_item['id'] ?>" class="action" title="Вопросы"><span class="uk-icon-question-circle"></span></a>
                            <?php
                            endif; //if ($is_can_question):
                            if ($is_can_testing):
                            ?>
                                <a href="/testing/index?<?= $url_testing_param .'&s_test='.$t_item['id'] ?>" class="action" title="Тестирование"><span class="uk-icon-gear"></span></a>
                            <?php endif; //if ($is_can_testing): ?>
                        </td>
                    </tr>
                    <?php
                endforeach; //foreach ($tests as $t_item):
            endif; //if (is_array($tests) && count($tests) > 0):
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
    </script>

<?php include APP_VIEWS . 'layouts/footer.php'; ?>