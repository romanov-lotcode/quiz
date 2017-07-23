<?php

$user_id = User::checkLogged();
if (!$user_id)
{
    header('Location: /main/login');
}

$user_rights = User::getUserRights($user_id);
$menu_panel = new Menu_Panel();
//$menu_builder = new Menu_Builder();
//$menu = $menu_builder->buildTree($menu_panel, $user_rights);

