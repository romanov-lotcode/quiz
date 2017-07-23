<?php
$pagetitle = 'Главная';
$page_id = 'page_index';


//Подключаем шапку
include APP_VIEWS . 'layouts/header.php';
?>
<div class="uk-width-1-2"><?php //print_r($user_rights) ?></div>
<div class="uk-width-1-2"><?php //print_r($menu_panel->getMenuPanel()) ?></div>

<?php include APP_VIEWS . 'layouts/footer.php'; ?>