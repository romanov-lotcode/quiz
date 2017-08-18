<?php
$pagetitle = 'Вопрос';
$page_id = 'page_moderator';


//Подключаем шапку
include APP_VIEWS . 'layouts/header.php';
?>

    <h1><?= $pagetitle ?></h1>
    <?php if (isset($search['test_id']) && $search['test_id'] != null): ?>
    <a class="back" href="/test/index?<?= $url_param ?>">&larr; Перейти к тесту</a>
    <?php endif; //if (isset($search['test_id']) && $search['test_id'] != null): ?>
    <div class="uk-width-8-10" align="left">

        <form method="GET" class="uk-form simple">
            <table class="uk-width-1-1 search_param">
                <input type="hidden" name="s_direction" value="<?= $search['direction_id'] ?>" />
                <input type="hidden" name="s_name" value="<?= $search['test_name'] ?>" />
                <input type="hidden" name="page" value="<?= $page ?>" />
                <input type="hidden" name="tid" value="<?= $search['test_id'] ?>" />
                <?php if ($test['name'] != null): ?>
                <tr>
                    <td class="uk-width-1-1"  colspan="3">
                        Тест: "<?= $test['name'] ?>"
                    </td>
                </tr>
                <?php endif; //if ($test['name'] != null): ?>

                <tr>
                    <td class="uk-width-2-3"  colspan="2">
                        <?= $html_element['name']->render() ?>
                    </td>
                    <td class="uk-width-1-3">
                        <button class="uk-button">Поиск</button>
                        <a href="/question/add?<?= $url_param ?>" class="uk-button fr" title="Добавить">Добавить</a>
                    </td>
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
            $i=0;
            if (is_array($questions) && count($questions) > 0):
                foreach ($questions as $q_item):
                    $index_number++;
                    $i++;
                    ?>

                    <tr class="srow">
                        <td><?= $index_number ?></td>
                        <td><?= $q_item['name'] ?></td>
                        <td><?= $app_state->getFlagState($q_item['flag']) ?></td>
                        <td>
                            <?php
                            if ($q_item['flag'] != FLAG_NO_CHANGE):
                                ?>
                                <a href="/question/edit?<?= $url_param . '&qid='.$q_item['id'] ?>" class="action" title="Редактировать"><span class="uk-icon-pencil"></span></a>
                                <a href="/question/delete?<?= $url_param . '&qid='.$q_item['id'] ?>" class="action" title="Удалить"><span class="uk-icon-trash"></span></a>
                                <?php
                            endif; //if ($q_item['flag'] != FLAG_NO_CHANGE):
                            if ($is_can_answer):
                                ?>
                                <a href="/answer/index?<?= $url_param .'&qid='.$q_item['id'] ?>" class="action" title="Ответы"><span class="uk-icon-commenting"></span></a>
                            <?php endif; //if ($is_can_answer): ?>
                        </td>
                    </tr>
                    <?php
                endforeach; //foreach ($questions as $q_item):
            endif; //if (is_array($questions) && count($questions) > 0):
            ?>

            <?php
            include APP_VIEWS . 'layouts/record_count.php';
            echo recordCount($total, $i);
            ?>

        </table>

    </div>

<?php include APP_VIEWS . 'layouts/footer.php'; ?>