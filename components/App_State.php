<?php

/**
 * Class App_State
 * Различные состояния проекта.
 */
class App_State
{
    /**
     * @param int $value - целое значение
     * @return string
     */
    public function getFlagState($value)
    {
        $result = 'Не определено';
        if ($value == 2)
        {
            $result = 'Выкл <span class="uk-icon-close" style="color: red;" title="Выключено"></span>';
        }
        if ($value == 1)
        {
            $result = 'Вкл <span class="uk-icon-check" style="color: green;" title="Включено"></span>';
        }
        if ($value == 0)
        {
            $result = 'Н.и. <span class="uk-icon-check" style="color: green;" title="Включено (Невозможно изменить)"></span>';
        }
        return $result;
    }
}