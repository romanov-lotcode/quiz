<?php

class BaseController
{
    private $user_right = false;
    private $menu_panel = false;
    private $can_view = false;

    public function __construct()
    {
        $user_id = User::checkLogged();
        $this->setUserRight(User::getUserRights($user_id));
        $menu_panel = new Menu_Panel();
        //$this->setMenuPanel($menu_panel);
        $this->setMenuPanel($menu_panel->render($this->getUserRight()));
    }

    public function setUserRight($value)
    {
        if (is_array($value) && count($value) > 0)
        {
            $this->user_right = $value;
            return true;
        }
        return false;
    }

    public function getUserRight()
    {
        return $this->user_right;
    }

    public function setMenuPanel($value)
    {
        if (!empty($value))
        {
            $this->menu_panel = $value;
            return true;
        }
        return false;
    }

    public function getMenuPanel()
    {
        return $this->menu_panel;
    }

    public function setCanView($value)
    {
        if (is_bool($value))
        {
            $this->can_view = $value;
            return true;
        }
        return false;
    }

    public function getCanView()
    {
        return $this->can_view;
    }
}