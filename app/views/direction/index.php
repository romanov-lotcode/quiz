<?php
$pagetitle = 'Направление';
$page_id = 'page_moderator';


//Подключаем шапку
include APP_VIEWS . 'layouts/header.php';
?>
    <div class="uk-width-1-2" align="left">
        <h1><?= $pagetitle ?></h1>
        <div class="uk-width-1-2"><?php print_r($directions) ?></div>
        <div class="uk-width-1-2"><?php echo $total_direction; ?></div>
    </div>



<?php include APP_VIEWS . 'layouts/footer.php'; ?>