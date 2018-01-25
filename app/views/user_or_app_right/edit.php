<?php
$pagetitle = 'Редактировать';
$page_id = 'page_administrator';

//Подключаем шапку
include APP_VIEWS . 'layouts/header.php';
?>
    <h1><?= $pagetitle ?></h1>
    <a class="back" href="/user_or_app_right/index?<?= $url_param ?>">&larr; Вернуться назад</a>

    <div data-uk-grid class="uk-width-1-2 uk-margin-large-top uk-align-center">
        <?php
        if (is_array($errors) && count($errors) > 0):
            foreach ($errors as $error):
                echo App_Message::getMessage($error, MESSAGE_TYPE_ERROR);
            endforeach; // foreach ($errors as $error):
        endif; //if (is_array($errors) && count($errors) > 0):
        ?>

        <form method="POST" class="uk-form">
            <div class="uk-form-row">
                <legend class="app">Редактировать</legend>
            </div>
            <div class="uk-form-row uk-width-1-1 uk-width-small-1-1">
                Пользователь: <?= trim($user['this_lastname'] . ' ' . $user['this_firstname']
                    . ' ' . $user['this_middlename']) ?>
            </div>
            <table class="uk-form-row uk-width-1-1" cellpadding="2">
                <tr>
                    <td class="uk-width-1-2" style="vertical-align: text-top;">
                        <div class="uk-form-row uk-width-1-1">
                            <label for="check_moderator_control" style="color: #101921; font-size: 14px;">
                                <i class="uk-icon-gear"></i> <input type="checkbox" id="check_moderator_control" /> Модератор
                            </label>
                        </div>
                        <?php if (is_array($moderator) && count($moderator) > 0): ?>
                            <?php
                            $i = 0;
                            foreach ($moderator as $m_key => $m_value):
                            ?>
                                <div class="uk-form-row uk-width-1-1">
                                    <?= $checkbox_element['moderator_'.$i]->render(); ?>
                                </div>
                            <?php
                                $i++;
                            endforeach; // foreach ($moderator as $m_key => $m_value):
                            ?>
                        <?php endif; // if (is_array($moderator) && count($moderator) > 0): ?>
                    </td>
                    <td class="uk-width-1-2" style="vertical-align: text-top;">
                        <div class="uk-form-row uk-width-1-1">
                            <label for="check_administrator_control" style="color: #101921; font-size: 14px;">
                                <i class="uk-icon-gears"></i> <input type="checkbox" id="check_administrator_control" /> Администратор
                            </label>
                        </div>
                        <?php if (is_array($administrator) && count($administrator) > 0): ?>
                            <?php
                            $i = 0;
                            foreach ($administrator as $a_key => $a_value):
                                ?>
                                <div class="uk-form-row uk-width-1-1">
                                    <?= $checkbox_element['administrator_'.$i]->render(); ?>
                                </div>
                                <?php
                                $i++;
                            endforeach; // foreach ($administrator as $a_key => $a_value):
                            ?>
                        <?php endif; // if (is_array($administrator) && count($administrator) > 0): ?>
                    </td>
                </tr>
            </table>
            <script  type="text/javascript">

                $(document).ready(function() {
                    $("#check_moderator_control").click(function () {
                        if ($("#check_moderator_control").is(":checked")) {
                            $(".moderator").prop("checked", true);
                            $("#check_moderator_control").prop("checked", true);
                        }
                        else {
                            $(".moderator").prop("checked", false);
                            $("#check_moderator_control").prop("checked", false);
                        }
                    });

                    $("#check_administrator_control").click(function () {
                        if (!$("#check_administrator_control").is(":checked")) {
                            $(".administrator").prop("checked", false);
                            $("#check_administrator_control").prop("checked", false);
                            ;
                        }
                        else {
                            $(".administrator").prop("checked", true);
                            $("#check_administrator_control").prop("checked", true);
                        }
                    });
                });
            </script>



            <div class="uk-form-row uk-width-1-1 uk-width-small-1-2">
                <?//= $html_element['middlename']->render(); ?>
            </div>


            <?php if ($user['flag'] != FLAG_NO_CHANGE || $user['id'] == $u_id): ?>
                <div class="uk-form-row uk-width-1-1">
                    <button class="uk-button" name="edit">Редактировать</button>
                </div>
            <?php endif; // if ($user['flag'] != FLAG_NO_CHANGE || $user['id'] == $u_id): ?>

        </form>
    </div>

<?php include APP_VIEWS . 'layouts/footer.php'; ?>