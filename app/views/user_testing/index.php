<?php
$pagetitle = 'Назначить тестирование';
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
                        <td class="uk-width-2-3"  colspan="2">
                            <?= $html_element['testing']->render($option_testing_list, $optgroup_testing_list) ?>
                        </td>
                        <td class="uk-width-1-3">
                            <?php if ($option_testing_list_selected == 0): ?>
                            <button class="uk-button">Поиск</button>
                            <?php endif; //if ($option_testing_list_selected == 0): ?>
                        </td>
                    </tr>
                    <?php
                endif; // if ($option_direction_selected > 0):
                ?>
                <?php
                if ($option_direction_selected > 0 && $option_testing_list_selected > 0):
                    ?>
                    <tr>
                        <td class="uk-width-2-3"  colspan="2">
                            <?= $html_element['user_groups']->render($option_user_groups, $optgroup_user_groups) ?>
                        </td>
                        <td class="uk-width-1-3">
                        </td>
                    </tr>
                    <tr>
                        <td class="uk-width-2-3"  colspan="2">
                            <?= $html_element['name']->render() ?>
                        </td>
                        <td class="uk-width-1-3">
                            <button class="uk-button">Поиск</button>
                        </td>
                    </tr>
                    <?php
                endif; // if ($option_direction_selected > 0 && $option_testing_list_selected > 0):
                ?>
            </table>
        </form>

        <?php
        if ($option_direction_selected > 0 && $option_testing_list_selected > 0 && $option_user_groups_selected > 0):
            ?>
        <?php
        if ($changes):
            echo App_Message::getMessage('Изменения внесены', MESSAGE_TYPE_SUCCESS);
        endif; //if ($changes):
        ?>
        <form method="POST">
            <table class="uk-width-1-1 view">
                <tr>
                    <th class="uk-width-1-10" align="center">
                        <input type="checkbox" id="checkAll" name="checkAll" onclick="checkboxes_select_all(this)">
                    </th>
                    <th class="uk-width-1-10">№</th>
                    <th class="uk-width-6-10">ФИО[логин]</th>
                    <th class="uk-width-1-10">Состояние</th>
                    <th class="uk-width-1-10">Действие</th>
                </tr>

                <?php
                $i=0;
                if (is_array($users) && count($users) > 0):
                    foreach ($users as $u_item):
                        $index_number++;
                        $i++;
                        ?>

                        <tr class="srow">
                            <td align="center">
                                <input type="checkbox" name="uid['<?= $i ?>']" value="<?= $u_item['user_id'] ?>"
                                    <?php
                                    foreach($testing_user_list as $tul_item):
                                    if($tul_item['user_id'] == $u_item['user_id']):
                                    ?>
                                       checked
                                    <?php
                                    break;
                                    endif;
                                    endforeach;
                                    ?>
                                />
                            </td>
                            <td><?= $index_number ?></td>
                            <td>
                                <?= trim($u_item['lastname'] . ' '
                                    . $u_item['firstname'] . ' '
                                    . $u_item['middlename'] .'['.$u_item['login'].']') ?>
                            </td>
                            <td><?= $app_state->getUserFlagState($u_item['user_flag']) ?></td>
                            <td>
                                <a href="/result/index?<?= $url_param . '&uid='.$u_item['user_id'].'&pf='.PAGE_FROM_USER_TESTING_INDEX ?>" class="action" title="Посмотреть результаты тестирования"><span class="uk-icon-sticky-note"></span></a>
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
            <?php if ($total > 0): ?>
            <br />
            <button class="uk-button" name="save">Сохранить изменения</button>
            <?php endif; // if ($total > 0): ?>
        </form>
        <?php
        endif; // if ($option_direction_selected > 0):
        ?>
    </div>
    <script src="<?= APP_TEMPLATES ?>css/chosen/chosen.jquery.js" type="text/javascript"></script>
    <script type="text/javascript">
        $("#s_direction").chosen({no_results_text: "Пока нет направлений", search_contains: true});
        $("#s_testing").chosen({no_results_text: "Пока нет тестирований", search_contains: true});
        $("#s_user_group").chosen({no_results_text: "Пока нет групп", search_contains: true});


        function checkboxes_select_all(obj)
        {
            // Получаем NodeList дочерних элементов input формы:
            var items = obj.form.getElementsByTagName("input"), len, i;

            for (i = 0, len = items.length; i < len; i++)
            {
                // Если текущий элемент является чекбоксом
                if (items.item(i).type && items.item(i).type === "checkbox")
                {
                    if (obj.checked)
                    {
                        // Устанавливаем отметки всем чекбоксам
                        items.item(i).checked = true;
                    }
                    else
                    {
                        // Иначе снимаем отметки со всех чекбоксов:
                        items.item(i).checked = false;
                    }
                }
            }
        }
    </script>

<?php include APP_VIEWS . 'layouts/footer.php'; ?>