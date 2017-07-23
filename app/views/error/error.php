<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<h3 align="center">Возникла ошибка</h3>
<?php
if (is_array($errors)):

    foreach ($errors as $key => $value):
?>
<p><?= $value.' ['.$key.']' ?></p>
<?php
    endforeach;
endif;
?>
<div align="center"><a href="/">Перейти на главуню</a></div>
</body>
</html>