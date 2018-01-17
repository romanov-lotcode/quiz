<?php
$pagetitle = 'Группы пользователя';
$page_id = 'page_moderator';


//Подключаем шапку
include APP_VIEWS . 'layouts/header.php';
?>

    <h1><?= $pagetitle ?></h1>
    <a class="back" href="/user/index?<?= $url_param ?>">&larr; Перейти к пользователю</a>
    <div class="uk-width-8-10" align="left">

        <table class="uk-width-1-1 search_param">
            <?php if ($user['this_lastname'] != null): ?>
                <tr>
                    <td class="uk-width-1-2"  colspan="2">
                        Пользователь: "<?= trim($user['this_lastname'] . ' ' . $user['this_firstname'] . ' ' . $user['this_middlename']) ?>"
                    </td>
                    <td class="uk-width-1-3">
                        <a href="/user_user_group/add?<?= $url_param . '&uid='.$search['uid'] ?>" class="uk-button fr" title="Добавить">Добавить</a>
                    </td>
                </tr>
            <?php endif; //if ($user['lastname'] != null): ?>
        </table>

        <table class="uk-width-1-1 view">
            <tr>
                <th class="uk-width-1-10">№</th>
                <th class="uk-width-8-10">Название</th>
                <th class="uk-width-1-10">Действие</th>
            </tr>

            <?php
            $i=0;
            if (is_array($groups) && count($groups) > 0):
                foreach ($groups as $g_item):
                    $index_number++;
                    $i++;
                    ?>

                    <tr class="srow">
                        <td><?= $index_number ?></td>
                        <td><?= $g_item['name'] ?></td>
                        <td>
                            <?php
                            if ($g_item['flag'] != FLAG_NO_CHANGE):
                                ?>
                                <a href="/user_user_group/delete?<?= $url_param .'&uid='.$search['uid']. '&uugid='.$g_item['id'] ?>" class="action" title="Исключить"><span class="uk-icon-trash"></span></a>
                                <?php
                            endif; //if ($g_item['flag'] != FLAG_NO_CHANGE):
                            ?>
                        </td>
                    </tr>
                    <?php
                endforeach; //foreach ($groups as $g_item):
            endif; //if (is_array($groups) && count($groups) > 0):
            ?>

            <?php
            include APP_VIEWS . 'layouts/record_count.php';
            echo recordCount($total, $i);
            ?>

        </table>

    </div>

<?php include APP_VIEWS . 'layouts/footer.php'; ?>