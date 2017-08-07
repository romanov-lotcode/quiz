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
        if ($value == 1)
        {
            $result = 'Вкл <span class="uk-icon-check" style="color: green;" title="Включено"></span>';
        }
        if ($value == 0)
        {
            $result = 'Выкл <span class="uk-icon-close" style="color: red;" title="Выключено"></span>';
        }
        return $result;
    }
}