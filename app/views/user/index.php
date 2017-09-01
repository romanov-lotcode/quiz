<?php
$pagetitle = 'Пользователь';
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
                        <?= $html_element['name']->render() ?>
                    </td>
                    <td class="uk-width-1-3">
                        <button class="uk-button">Поиск</button>
                        <a href="/user/add?<?= $url_param ?>" class="uk-button fr" title="Добавить">
                            Добавить
                        </a>
                    </td>
                </tr>
            </table>
        </form>

        <table class="uk-width-1-1 view">
            <tr>
                <th class="uk-width-1-10">№</th>
                <th class="uk-width-5-10">ФИО</th>
                <th class="uk-width-1-10">Логин</th>
                <th class="uk-width-1-10">Состояние</th>
                <th class="uk-width-2-10">Действие</th>
            </tr>

            <?php
            $i=0;
            if (is_array($users) && count($users) > 0):
                foreach ($users as $u_item):
                    $index_number++;
                    $i++;
                    ?>

                    <tr class="srow">
                        <td><?= $index_number ?></td>
                        <td><?= trim($u_item['lastname'] . ' ' . $u_item['firstname'] . ' ' . $u_item['middlename']); ?></td>
                        <td><?= $u_item['login'] ?></td>
                        <td><?= $app_state->getUserFlagState($u_item['flag']) ?></td>
                        <td>
                            <?php
                            if ($u_item['flag'] != FLAG_NO_CHANGE || $u_item['id'] == $u_id):
                                ?>
                                <a href="/user/edit?<?= $url_param . '&uid='.$u_item['id'] ?>" class="action" title="Редактировать"><span class="uk-icon-pencil"></span></a>
                                <a href="/user/delete?<?= $url_param . '&uid='.$u_item['id'] ?>" class="action" title="Удалить"><span class="uk-icon-trash"></span></a>
                                <a href="/user/password?<?= $url_param . '&uid='.$u_item['id'] ?>" class="action" title="Изменить пароль"><span class="uk-icon-lock" style="padding-left: 5px; padding-right: 5px;"></span></a>
                                <?php
                            endif; //if ($u_item['flag'] != FLAG_NO_CHANGE || $u_item['id'] == $u_id):
                            ?>
                            <a href="/result/index?<?= $url_param . '&uid='.$u_item['id'].'&pf='.PAGE_FROM_USER_INDEX ?>" class="action" title="Посмотреть результаты тестирования"><span class="uk-icon-sticky-note"></span></a>
                        </td>
                    </tr>
                    <?php
                endforeach; //foreach ($users as $u_item):
            endif; //if (is_array($users) && count($users) > 0):
            ?>

            <?php
            include APP_VIEWS . 'layouts/record_count.php';
            echo recordCount($total, $i);
            ?>

        </table>

        <?= $pagination->get() ?>

    </div>

<?php include APP_VIEWS . 'layouts/footer.php'; ?>