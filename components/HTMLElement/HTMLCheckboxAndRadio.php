<?php

namespace HTMLElement;


class HTMLCheckboxAndRadio extends HTMLElementBase
{
    /*******************************************************
     ********************* Поля класса *********************
     *******************************************************/

    // Установки конфигурации элемента
    const HTML_E_CONFIG_VALUE_NAME = 'value';

    /**
     * Флаг для элемента.
     * Флаг может принимать 2 состояния (отмечен/не отмечен).
     * true - элемент отмечен.
     * false - элемент не отмечен.
     * @var bool
     */
    private $checked = false;

    /*******************************************************
     ******************** Методы класса ********************
     *******************************************************/
    /**
     * Устанавливает значение элемента.
     * @param $value string - значение
     */
    public function setValue($value)
    {
        parent::setConfig(self::HTML_E_CONFIG_VALUE_NAME, $value);
    }

    /**
     * Возвращает значение элемента.
     * @return bool OR string
     */
    public function getValue()
    {
        return parent::getConfig(self::HTML_E_CONFIG_VALUE_NAME);
    }

    /**
     * Устанавливает флаг для элемента.
     * @param $value bool - флаг элемента
     * @return bool
     */
    public function setChecked($value)
    {
        if (!is_bool($value))
        {
            return false;
        }
        $this->checked = $value;
    }

    /**
     * Возвращает значение флага элемента.
     * @return bool
     */
    public function getChecked()
    {
        return $this->checked;
    }
}