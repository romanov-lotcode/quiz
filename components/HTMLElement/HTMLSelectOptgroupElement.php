<?php

namespace HTMLElement;


class HTMLSelectOptgroupElement
{
    /*******************************************************
     ********************* Поля класса *********************
     *******************************************************/

    // Установки конфигурации элемента
    const HTML_E_CONFIG_LABEL_NAME = 'label';
    const HTML_E_CONFIG_STYLE_NAME = 'style';
    const HTML_E_CONFIG_CLASS_NAME = 'class';


    /**
     * Текущее поле хранит в себе конфигурацию
     * HTML элемента (набор атрибутов) с их значением
     * в паре ключ=>значение.
     * Пример: name='someName', value='someValue' и т.п.
     * @var array
     */
    private $element_config = [];

    /**
     * Значение для
     * @var string
     */
    private $label = false;


    /*******************************************************
     ******************** Методы класса ********************
     *******************************************************/

    /**
     * Устанавливает значение атрибута элемента.
     * @param $key string
     * @param $value string
     */
    public function setConfig($key, $value)
    {
        __setConfig($this->element_config, $key, $value);
    }

    /**
     * Возвращает значение атрибута по ключу.
     * @param $key string - ключ
     * @return bool OR string
     */
    public function getConfig($key)
    {
        if (!isset($this->element_config[$key]))
        {
            return false;
        }
        return $this->element_config[$key];
    }

    /**
     * Возвращает конфигурацию элемента.
     * @return array
     */
    public function getFullConfig()
    {
        return $this->element_config;
    }

    /**
     * Устанавливает метку элемента.
     * @param $value string - метка
     */
    public function setLabel($value)
    {
        $this->setConfig(self::HTML_E_CONFIG_LABEL_NAME, $value);
    }

    /**
     * Возвращает метку элемента.
     * @return bool OR string
     */
    public function getLabel()
    {
        return $this->getConfig(self::HTML_E_CONFIG_LABEL_NAME);
    }

    /**
     * Устанавливает значение стиля.
     * @param $value string - значение стиля.
     * @return bool
     */
    public function setStyle($value)
    {
        if (empty($value))
        {
            return false;
        }
        $this->element_config[self::HTML_E_CONFIG_STYLE_NAME] .= ' ' . $value;
    }

    /**
     * Добавляет класс стиля к атрибуту class
     * @param $value
     * @return bool
     */
    public function addStyleClass($value)
    {
        if (empty($value))
        {
            return false;
        }
        $this->element_config[self::HTML_E_CONFIG_CLASS_NAME] .= ' ' . $value;
    }

    /**
     * Отрисовывает открывающий тег html элемента.
     * @return string
     */
    public function renderOpen()
    {
        $el_attributes = '';
        $full_config = $this->getFullConfig();

        foreach ($full_config as $key => $val)
        {
            if ($val !== false)
            {
                if ($el_attributes != '') $el_attributes .= ' ';

                $el_attributes .= $key .'="'.$val.'"';
            }
        }

        return '<optgroup '. $el_attributes .'>';
    }

    /**
     * Отрисовывает закрывающий тег html элемент.
     * @return string
     */
    public static function renderClose()
    {
        return '</optgroup>';
    }
}