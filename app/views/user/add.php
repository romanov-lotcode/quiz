<?php
$pagetitle = 'Пользователь';
$page_id = 'page_moderator';

//Подключаем шапку
include APP_VIEWS . 'layouts/header.php';
?>
    <script src="<?= APP_TEMPLATES ?>js/form-password.min.js"></script>

    <h1><?= $pagetitle ?></h1>
    <a class="back" href="/user/index?<?= $url_param ?>">&larr; Вернуться назад</a>

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
                <legend class="app">Добавить</legend>
            </div>
            <div class="uk-form-row uk-width-1-1 uk-width-small-1-2">
                <?= $html_element['lastname']->render(); ?>
            </div>
            <div class="uk-form-row uk-width-1-1 uk-width-small-1-2">
                <?= $html_element['firstname']->render(); ?>
            </div>
            <div class="uk-form-row uk-width-1-1 uk-width-small-1-2">
                <?= $html_element['middlename']->render(); ?>
            </div>
            <div class="uk-form-row uk-width-1-1 uk-width-small-1-2">
                <?= $html_element['login']->render(); ?>
            </div>
            <div class="uk-form-row">
                <div class="uk-form-password uk-width-1-1 uk-width-small-1-2">
                    <?= $html_element['password']->render(); ?>
                    <a href class="uk-form-password-toggle" id="pass_sh_h" data-uk-form-password style="margin-top: 3px;">
                        Показать
                    </a>
                </div>
            </div>
            <div class="uk-form-row uk-width-1-1 uk-width-small-1-2">
                <?= $html_element['email']->render(); ?>
            </div>
            <div class="uk-form-row uk-width-1-1">
                <?= $html_element['flag']->render($option_flag, $optgroup_flag) ?>
            </div>

            <div class="uk-form-row">
                <button class="uk-button" name="add">Добавить</button>
            </div>

        </form>
    </div>

<?php include APP_VIEWS . 'layouts/footer.php'; ?>