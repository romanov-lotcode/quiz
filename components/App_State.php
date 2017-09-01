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

    /**
     * @param int $value - целое значение
     * @return string
     */
    public function getUserFlagState($value)
    {
        $result = 'Не определено';
        if ($value == 2)
        {
            $result = 'Неактивен <span class="uk-icon-close" style="color: red;" title="Неактивен"></span>';
        }
        if ($value == 1)
        {
            $result = 'Активен <span class="uk-icon-check" style="color: green;" title="Активен"></span>';
        }
        if ($value == 0)
        {
            $result = 'Н.и. <span class="uk-icon-check" style="color: green;" title="Активен (Невозможно изменить)"></span>';
        }
        return $result;
    }

    /**
     * @param int $value - целое значение
     * @return string
     */
    public function getTimeFlagState($value)
    {
        $result = '';
        if ($value == 2)
        {
            $result = '<span class="uk-icon-ban" style="color: red;" title="Выключено"></span>';
        }
        if ($value == 1)
        {
            $result = '<span class="uk-icon-clock-o" style="color: green;" title="Включено"></span>';
        }
        return $result;
    }
}