<?php
$pagetitle = 'Пользователь';
$page_id = 'page_moderator';

//Подключаем шапку
include APP_VIEWS . 'layouts/header.php';
?>
    <h1><?= $pagetitle ?></h1>
    <a class="back" href="/user/index?<?= $url_param ?>">&larr; Вернуться назад</a>
    <script src="<?= APP_TEMPLATES ?>js/form-password.min.js"></script>

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
            <?php if ($user['this_lastname'] != null): ?>
            <div class="uk-form-row uk-width-1-1 uk-width-small-1-2">
                <?= trim($user['this_lastname']. ' ' .$user['this_firstname'].' '.$user['this_middlename']) ?>
            </div>
            <?php endif; //if ($user['this_lastname'] != null): ?>
            <div class="uk-form-row">
                <div class="uk-form-password uk-width-1-1 uk-width-small-1-2">
                    <?= $html_element['password']->render(); ?>
                    <a href class="uk-form-password-toggle" id="pass_sh_h" data-uk-form-password style="margin-top: 3px;">
                        Показать
                    </a>
                </div>
            </div>

            <?php
            include APP_VIEWS . 'layouts/description.php';
            echo renderDescriptionDatetimeFIO($user);
            ?>
            <?php if ($user['flag'] != FLAG_NO_CHANGE || $user['id'] == $u_id): ?>
                <div class="uk-form-row uk-width-1-1">
                    <button class="uk-button" name="edit">Редактировать</button>
                </div>
            <?php endif; //if ($user['flag'] == NO_CHANGE || $user['id'] == $u_id): ?>

        </form>
    </div>

<?php include APP_VIEWS . 'layouts/footer.php'; ?>