<?php

namespace HTMLElement;


class HTMLTextElement extends HTMLElementBase
{

    /*******************************************************
     ********************* Поля класса *********************
     *******************************************************/

    // Установки конфигурации элемента
    const HTML_E_CONFIG_VALUE_NAME = 'value';

    private $min = false;
    private $max = false;

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
     * Установить значение из запроса.
     * @return bool
     */
    public function setValueFromRequest()
    {
        if (!isset($_REQUEST[$this->getName()]))
        {
            return false;
        }
        $this->setValue(htmlspecialchars($_REQUEST[$this->getName()], null, parent::getDefaultCharset()));
    }

    /**
     * Устанавливает минимальное значение.
     * @param $value value - минимальное значение
     */
    public function setMin($value)
    {
        if (is_int($value))
        {
            $this->min = $value;
        }
    }

    /**
     * Возвращает минимальное значение типа.
     * @return bool OR value
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * Устанавливает максимальное значение.
     * @param $value value - максимальное значение
     */
    public function setMax($value)
    {
        if (is_int($value))
        {
            $this->max = $value;
        }
    }

    /**
     * Возвращает максимальное значение.
     * @return bool OR value
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * Устанавилвает минимальное и максимальное значения.
     * @param $min value - минимальное значение
     * @param $max value - максимальное значение
     */
    public function setMinMax($min, $max)
    {
        $this->setMin($min);
        $this->setMax($max);
    }
}