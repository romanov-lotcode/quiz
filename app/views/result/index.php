<?php
$pagetitle = 'Результаты';
$page_id = 'page_result';


//Подключаем шапку
include APP_VIEWS . 'layouts/header.php';
?>

    <h1><?= $pagetitle ?></h1>
    <a class="back" href="<?= $url_link ?>">&larr; Вернуться назад</a>
    <div class="uk-width-8-10" align="left">
        <?php if ($is_can): ?>
            <?php
            if (is_array($errors) && count($errors) > 0):
                foreach ($errors as $error):
                    echo App_Message::getMessage($error, MESSAGE_TYPE_ERROR);
                endforeach; // foreach ($errors as $error):
            endif; //if (is_array($errors) && count($errors) > 0):
            ?>
            <form method="GET" class="uk-form simple">
                <?php
                if ($search['pf'] == 'uti'):
                ?>
                    <input type="hidden" name="pf" value="<?= $search['pf'] ?>" />
                    <input type="hidden" name="s_direction" value="<?= $search['s_direction'] ?>" />
                    <input type="hidden" name="s_testing" value="<?= $search['s_testing'] ?>" />
                    <input type="hidden" name="s_user_group" value="<?= $search['s_user_group'] ?>" />
                    <input type="hidden" name="s_name" value="<?= $search['s_name'] ?>" />
                <?php
                endif; //if ($search['pf'] == 'uti'):
                ?>
                <?php
                if ($search['pf'] == 'ui'):
                ?>
                    <input type="hidden" name="pf" value="<?= $search['pf'] ?>" />
                    <input type="hidden" name="s_name" value="<?= $search['s_name'] ?>" />
                    <input type="hidden" name="p_page" value="<?= $search['p_page'] ?>" />
                <?php
                endif; //if ($search['pf'] == 'ui'):
                ?>
                <input type="hidden" name="page" value="<?= $search['page'] ?>" />
                <input type="hidden" name="type" value="<?= $search['type'] ?>" />
            </form>

            <table class="uk-width-1-1 view">
                <tr>
                    <th class="uk-width-1-10">№</th>
                    <th class="uk-width-6-10" title="Название тестирования">Название</th>
                    <th class="uk-width-2-10" title="Дата завершения тестирования">Дата завершения</th>
                    <th class="uk-width-1-10">Действие</th>
                </tr>

                <?php
                $i=0;
                if (is_array($testing_results) && count($testing_results) > 0):
                    foreach ($testing_results as $tr_item):
                        $index_number++;
                        $i++;
                    ?>
                    <tr class="srow">
                        <td><?= $index_number ?></td>
                        <td>
                            <?= $tr_item['testing_name'] ?>
                            <div class="uk-comment-meta">
                                Группа: <?= $tr_item['user_group_name'] ?>
                            </div>
                        </td>
                        <td>
                            <?php
                            if ($tr_item['end_datetime'] != null)
                            {
                                echo $date_converter->datetimeToString($tr_item['end_datetime']);
                            }
                            else
                            {
                                echo '-';
                            }
                            ?>
                        </td>
                        <td>
                            <?php if ($tr_item['end_datetime'] != null): ?>
                            <a href="/result/view?<?= $url_param . '&testing_result_id='.$tr_item['id'] .'&user_id='.$user_id_to_view ?>" class="action" title="Посмотреть результат"><span class="uk-icon-eye"></span></a>
                            <?php endif; // if ($tr_item['end_datetime'] != null): ?>
                            <?php if ($is_can_moderator_result): ?>
                            <a href="/result/delete?<?= $url_param . '&testing_result_id='.$tr_item['id'] .'&user_id='.$user_id_to_view ?>" class="action" title="Удалить результат"><span class="uk-icon-trash"></span></a>
                            <?php endif; // if ($is_can_moderator_result): ?>
                        </td>
                    </tr>
                    <?php
                    endforeach; // foreach ($testing_results as $tr_item):
                endif; // if (is_array($testing_results) && count($testing_results) > 0):
                ?>
                <?php
                include APP_VIEWS . 'layouts/record_count.php';
                echo recordCount($total, $i);
                ?>
            </table>
            <?= $pagination->get() ?>
        <?php endif; //if ($is_can): ?>
    </div>


<?php include APP_VIEWS . 'layouts/footer.php'; ?>