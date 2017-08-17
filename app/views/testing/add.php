<?php
$pagetitle = 'Тестирование';
$page_id = 'page_moderator';

//Подключаем шапку
include APP_VIEWS . 'layouts/header.php';
?>

    <h1><?= $pagetitle ?></h1>
    <a class="back" href="/testing/index?<?= $url_param ?>">&larr; Вернуться назад</a>

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
                <?php if($test['name'] != null): ?>
                Тест: "<?= $test['name'] ?>"
                <?php endif; //if($test['name'] != null) ?>
            </div>
            <div class="uk-form-row uk-width-1-1">
                <?= $html_element['name']->render(); ?>
            </div>
            <div class="uk-form-row uk-width-1-1">
                <?= $html_element['testing_count']->render(); ?>
            </div>
            <div class="uk-form-row uk-width-1-1">
                <?= $html_element['question_count']->render(); ?>
            </div>
            <div class="uk-form-row uk-width-1-1">
                <?= $html_element['is_question_random']->render($option_is_question_random, $optgroup_is_question_random); ?>
            </div>
            <div class="uk-form-row uk-width-1-1">
                <?= $html_element['is_answer_random']->render($option_is_answer_random, $optgroup_is_answer_random); ?>
            </div>
            <div class="uk-form-row uk-width-1-1">
                <?= $html_element['minimum_score']->render(); ?>
            </div>
            <div class="uk-form-row uk-width-1-1">
                <table class="uk-width-1-1 search_param">
                    <tr>
                        <td align="center" colspan="3"><label>Время прохождения</label></td>
                    </tr>
                    <tr>
                        <td class="uk-width-1-3">
                            <?= $html_element['hour']->render(); ?>
                        </td>
                        <td class="uk-width-1-3">
                            <?= $html_element['minute']->render(); ?>
                        </td>
                        <td class="uk-width-1-3">
                            <?= $html_element['second']->render(); ?>
                        </td>
                    </tr>
                    </table>
            </div>
            <div class="uk-form-row uk-width-1-1">
                <?= $html_element['testing_time_flag']->render($option_testing_time_flag, $optgroup_testing_time_flag); ?>
            </div>
            <div class="uk-form-row uk-width-1-1">
                <?= $html_element['flag']->render($option_flag, $optgroup_flag) ?>
            </div>

            <div class="uk-form-row uk-width-1-1">
                <button class="uk-button" name="add">Добавить</button>
            </div>

        </form>
    </div>

<?php include APP_VIEWS . 'layouts/footer.php'; ?>