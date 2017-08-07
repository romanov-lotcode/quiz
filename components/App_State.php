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
            $result = 'Вкл';
        }
        if ($value == 0)
        {
            $result = 'Выкл';
        }
        return $result;

    }
}