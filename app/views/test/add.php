<?php
$pagetitle = 'Тест';
$page_id = 'page_moderator';

//Подключаем шапку
include APP_VIEWS . 'layouts/header.php';
?>

    <h1><?= $pagetitle ?></h1>
    <a class="back" href="/test/index?<?= $url_param ?>">&larr; Вернуться назад</a>

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
            <div class="uk-form-row uk-width-1-1">
                <?= $html_element['direction']->render($option_direction, $optgroup_direction) ?>
            </div>
            <div class="uk-form-row uk-width-1-1">
                <?= $html_element['name']->render(); ?>
            </div>
            <div class="uk-form-row uk-width-1-1">
                <?= $html_element['comment']->render(); ?>
            </div>
            <div class="uk-form-row uk-width-1-1">
                <?= $html_element['flag']->render($option_flag, $optgroup_flag) ?>
            </div>

            <div class="uk-form-row uk-width-1-1">
                <button class="uk-button" name="add">Добавить</button>
            </div>

        </form>
    </div>
    <script src="<?= APP_TEMPLATES ?>css/chosen/chosen.jquery.js" type="text/javascript"></script>
    <script type="text/javascript">
        $("#direction_id").chosen({no_results_text: "Пока нет направлений", search_contains: true});
        <?php
        if (isset($errors['direction']) && $errors['direction'] != null):
        ?>
        var direction_id_chosen = document.getElementById('direction_id_chosen');
        direction_id_chosen.className += ' ch_danger';
        <?php
        endif; //if ():
        ?>
    </script>

<?php include APP_VIEWS . 'layouts/footer.php'; ?>