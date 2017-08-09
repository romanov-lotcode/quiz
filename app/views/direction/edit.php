<?php
$pagetitle = 'Направление';
$page_id = 'page_moderator';

//Подключаем шапку
include APP_VIEWS . 'layouts/header.php';
?>

    <h1><?= $pagetitle ?></h1>
    <a class="back" href="/direction/index?<?= $url_param ?>">&larr; Вернуться назад</a>

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
            <div class="uk-form-row uk-width-1-1">
                <?= $html_element['name']->render(); ?>
            </div>
            <div class="uk-form-row uk-width-1-1">
                <?= $html_element['flag']->render($option_flag, $optgroup_flag) ?>
            </div>
            <?php
            include APP_VIEWS . 'layouts/description.php';
            echo renderDescriptionDatetimeFIO($direction);
            ?>
            <?php if ($direction['flag'] != FLAG_NO_CHANGE): ?>
            <div class="uk-form-row">
                <button class="uk-button" name="edit">Редактировать</button>
            </div>
            <?php endif; //if ($direction['flag'] == NO_CHANGE): ?>

        </form>
    </div>

<?php include APP_VIEWS . 'layouts/footer.php'; ?>