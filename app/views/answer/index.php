<?php
$pagetitle = 'Ответ';
$page_id = 'page_moderator';


//Подключаем шапку
include APP_VIEWS . 'layouts/header.php';
?>

    <h1><?= $pagetitle ?></h1>
<?php if (isset($search['question_id']) && $search['question_id'] != null): ?>
    <?php if ($is_can_question): ?>
        <a class="back" href="/question/index?<?= $url_param ?>">&larr; Перейти к вопросу</a>
    <?php endif; //if ($is_can_question): ?>
<?php endif; //if (isset($search['test_id']) && $search['test_id'] != null): ?>
    <div class="uk-width-8-10" align="left">

        <table class="uk-width-1-1 search_param">
            <?php if ($question['name'] != null): ?>
                <tr>
                    <td class="uk-width-1-2"  colspan="2">
                        Вопрос: "<?= $question['name'] ?>"
                    </td>
                    <td class="uk-width-1-3">
                        <a href="/answer/add?<?= $url_param ?>" class="uk-button fr" title="Добавить">Добавить</a>
                    </td>
                </tr>
            <?php endif; //if ($question['name'] != null): ?>
        </table>

        <table class="uk-width-1-1 view">
            <tr>
                <th class="uk-width-1-10">№</th>
                <th class="uk-width-6-10">Название</th>
                <th class="uk-width-1-10" title="Коэффициент сложности">Коэффициент</th>
                <th class="uk-width-1-10">Состояние</th>
                <th class="uk-width-1-10">Действие</th>
            </tr>

            <?php
            $i=0;
            if (is_array($answers) && count($answers) > 0):
                foreach ($answers as $a_item):
                    $index_number++;
                    $i++;
                    ?>

                    <tr class="srow">
                        <td><?= $index_number ?></td>
                        <td><?= $a_item['name'] ?></td>
                        <td><?= $a_item['complexity_coefficient'] ?></td>
                        <td><?= $app_state->getFlagState($a_item['flag']) ?></td>
                        <td>
                            <?php
                            if ($a_item['flag'] != FLAG_NO_CHANGE):
                            ?>
                                <a href="/answer/edit?<?= $url_param . '&aid='.$a_item['id'] ?>" class="action" title="Редактировать"><span class="uk-icon-pencil"></span></a>
                                <a href="/answer/delete?<?= $url_param . '&aid='.$a_item['id'] ?>" class="action" title="Удалить"><span class="uk-icon-trash"></span></a>
                            <?php
                            endif; //if ($a_item['flag'] != FLAG_NO_CHANGE):
                            ?>
                        </td>
                    </tr>
                    <?php
                endforeach; //foreach ($answers as $a_item):
            endif; //if (is_array($answers) && count($answers) > 0):
            ?>

            <?php
            include APP_VIEWS . 'layouts/record_count.php';
            echo recordCount($total, $i);
            ?>

        </table>

    </div>

<?php include APP_VIEWS . 'layouts/footer.php'; ?>