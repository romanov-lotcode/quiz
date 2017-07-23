<?php

class Menu_Panel
{
    // Панель меню
    private $menu_panel = [];

    private $counter = 0;

    public function __construct()
    {
        $this->setMenuPanel(self::getMenu());
    }


    /**
     * Устанавливает значение панели меню.
     * @param array $value - панель меню
     * @return bool
     */
    public function setMenuPanel($value)
    {
        if (is_array($value) && count($value)>0)
        {
            $this->menu_panel = $value;
        }
        return false;
    }

    /**
     * Возвращает значение панели меню.
     * @return array
     */
    public function getMenuPanel()
    {
        return $this->menu_panel;
    }

    /**
     * Получает меню из БД.
     * @return array
     */
    private static function getMenu()
    {
        $sql = 'SELECT
          *
          FROM menu_panel
          WHERE menu_panel.flag > 0
          ORDER BY menu_panel.index_number';
        $db = Database::getConnection();
        $result = $db->query($sql);

        $menu_panel = [];
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $menu_panel[$row['parent_id']][] = $row;
            $i++;
        }
        return $menu_panel;
    }

    public function render($members = [], $parent_id = 0)
    {
        $trees = $this->getMenuPanel();
        $menu = '';
        if ($this->counter == 0)
        {
            $menu = '<ul class="uk-navbar-nav uk-hidden-small">';
            $this->counter++;
        }
        /*else
        {
            $menu = '<ul class="uk-nav uk-nav-dropdown">';
        }*/


        if (isset($trees[$parent_id]))
        {
            foreach ($trees[$parent_id] as $tree)
            {
                foreach ($members as $member)
                {
                    if ($member['right_value'] == $tree['member'])
                    {
                        $menu .='<li id="page_'. $tree['page_name'] .'"';
                        if ($tree['type'] == 3)
                        {
                            $menu .= ' class="uk-nav-divider">';
                            $menu .='<a href="' .$tree['url_address']. '"'
                                /*. (!empty($tree['title']))? ' title="'. $tree['title']
                                .'"' :''*/ .'><i class="uk-icon-'. $tree['icon_name'] .'"></i> ' .$tree['name']. '</a>';
                        }
                        elseif ($tree['type'] == 2)
                        {
                            $menu .= ' data-uk-dropdown="{mode:\'click\'}" aria-haspopup="true" aria-expanded="false">';
                            $menu .='<a href="' .$tree['url_address']. '"'
                                /*. (!empty($tree['title']))? ' title="'. $tree['title']
                                .'"' :''*/ .'><i class="uk-icon-'. $tree['icon_name'] .'"></i> ' .$tree['name']. ' <i class="uk-icon-caret-down"></i></a>';

                            $menu .= '<div class="uk-dropdown uk-dropdown-navbar uk-dropdown-bottom" style="top: 27px; left: 0px;">';
                            $menu .= '<ul class="uk-nav uk-nav-dropdown"><li>';
                        }
                        else
                        {
                            $menu .= '>';
                            $menu .='<a href="' .$tree['url_address']. '"'
                                /*. (!empty($tree['title']))? ' title="'. $tree['title']
                                .'"' :''*/ .'><i class="uk-icon-'. $tree['icon_name'] .'"></i> ' .$tree['name']. '</a>';
                        }

                        $menu .= $this->render($members, $tree['id']);
                        if ($tree['type'] == 2)
                        {
                            $menu .= '</ul>';
                            $menu .= '</div>';
                        }
                        $menu .= '</li>';
                    }
                }

            }
            $menu .= '</ul>';
        }
        else
        {
            return null;
        }

        return $menu;
    }
}