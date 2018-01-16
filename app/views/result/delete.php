<?php
$pagetitle = 'Результат тестирования';
$page_id = 'page_result';

//Подключаем шапку
include APP_VIEWS . 'layouts/header.php';
?>

    <h1><?= $pagetitle ?></h1>
    <a class="back" href="/result/index?<?= $url_param ?>">&larr; Вернуться назад</a>

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
                <legend class="app">Удалить</legend>
            </div>
            <div class="uk-form-row uk-width-1-1">
                <?php
                if ($testing_result_info['testing_name'] != null)
                {
                    echo 'Результат тестирования с названием: "' . $testing_result_info['testing_name'] . '"';
                }
                ?>
            </div>

            <?php if ($is_can): ?>
                <div class="uk-form-row uk-width-1-1">
                    Желаете удалить?
                </div>
                <div class="uk-form-row uk-width-1-1">
                    <button class="uk-button" name="yes">Да</button>
                    <button class="uk-button" name="no">Нет</button>
                </div>
            <?php endif; //if ($is_can): ?>

        </form>
    </div>

<?php include APP_VIEWS . 'layouts/footer.php'; ?>