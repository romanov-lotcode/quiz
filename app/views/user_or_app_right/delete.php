<?php
$pagetitle = 'Пользователь';
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
                <legend class="app">Удалить</legend>
            </div>
            <div class="uk-form-row uk-width-1-1">
                <?php
                if ($user['this_lastname'] != null)
                {
                    echo 'Пользователь: "' . trim($user['this_lastname'] . ' '
                            .$user['this_firstname'] . ' ' . $user['this_middlename']) . '"';
                }
                ?>
            </div>

            <?php if ($user['flag'] != FLAG_NO_CHANGE): ?>
                <div class="uk-form-row uk-width-1-1">
                    Желаете удалить?
                </div>
                <div class="uk-form-row uk-width-1-1">
                    <button class="uk-button" name="yes">Да</button>
                    <button class="uk-button" name="no">Нет</button>
                </div>
            <?php endif; //if ($user['flag'] == NO_CHANGE): ?>

        </form>
    </div>

<?php include APP_VIEWS . 'layouts/footer.php'; ?>