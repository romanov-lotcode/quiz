<?php
$pagetitle = 'Тест';
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
                        <button class="uk-button">Поиск</button>
                        <a href="/test/add?<?= $url_param ?>" class="uk-button fr" title="Добавить">Добавить</a>
                    </td>
                </tr>
                <tr>
                    <td class="uk-width-1-3">
                        <?= $html_element['name']->render() ?>
                    </td>
                    <td class="uk-width-1-3">

                    </td>
                    <td class="uk-width-1-3">
                    </td>
                </tr>

            </table>
        </form>

        <div>
            <?php print_r($tests); ?>
        </div>


    </div>
    <script src="<?= APP_TEMPLATES ?>css/chosen/chosen.jquery.js" type="text/javascript"></script>
    <script type="text/javascript">
        $("#direction").chosen({no_results_text: "Пока нет направлений", search_contains: true});
    </script>

<?php include APP_VIEWS . 'layouts/footer.php'; ?>