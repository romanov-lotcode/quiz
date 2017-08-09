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
                        <td class="uk-width-1-3">

                        </td>
                        <td class="uk-width-1-3">

                        </td>
                        <td class="uk-width-1-3" align="right">
                            <a href="/test/add?<?= $url_param ?>" class="uk-button" title="Добавить">
                                Добавить
                            </a>
                        </td>
                    </tr>
                </table>
            </form>



        </div>

<?php include APP_VIEWS . 'layouts/footer.php'; ?>