<?php

namespace HTMLElement;


class HTMLSelectOptionElement
{
    /*******************************************************
     ********************* Поля класса *********************
     *******************************************************/

    const HTML_E_CONFIG_VALUE_NAME = 'value';
    const HTML_E_CONFIG_TEXT_NAME = 'text';
    const HTML_E_CONFIG_ID_NAME = 'id';
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
     * Текст элемента.
     * @var string
     */
    private $text = false;

    /**
     * Группа элемента.
     * @var string
     */
    private $group = null;

    /**
     * Флаг для элемента.
     * Флаг может принимать 2 состояния (выбран/не выбран).
     * true - элемент выбран.
     * false - элемент не выбран.
     * @var bool
     */
    private $selected = false;

    /*******************************************************
     ******************** Методы класса ********************
     *******************************************************/

    /**
     * Устанавливает значение атрибута элемента.
     * @param $key string - ключ
     * @param $value string - значение
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
     * Устанавливает значение для элемента.
     * @param $value string - значение
     */
    public function setValue($value)
    {
        $this->setConfig(self::HTML_E_CONFIG_VALUE_NAME, $value);
    }

    /**
     * Возвращает значение элемента.
     * @return bool OR string
     */
    public function getValue()
    {
        return $this->getConfig(self::HTML_E_CONFIG_VALUE_NAME);
    }

    /**
     * Устанавливает текст элемента.
     * @param $value string - текст.
     * @return bool
     */
    public function setText($value)
    {
        if (empty($value))
        {
            return false;
        }
        $this->text = $value;
    }

    /**
     * Возращает текст элемента.
     * @return bool OR text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Устанавилвает группу элемента.
     * @param $value string - группа
     * @return bool
     */
    public function setGroup($value)
    {
        if (empty($value))
        {
            return false;
        }
        $this->group = $value;
    }

    /**
     * Возращает группу элемента.
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Устанавливает id элемента.
     * @param $value string - id.
     */
    public function setId($value)
    {
        $this->setConfig(self::HTML_E_CONFIG_ID_NAME, $value);
    }

    /**
     * Возвращает id элемента.
     * @return bool OR string
     */
    public function getId()
    {
        return $this->getConfig(self::HTML_E_CONFIG_ID_NAME);
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
     * @param $value string - класс
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
     * Отрисовывает html элемент.
     * @return string
     */
    public function render()
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

        if ($this->getSelected() === true)
        {
            $el_attributes .= ' selected';
        }

        return '<option '. $el_attributes .'>'
        . (($this->getText() !== false)? $this->getText() : '') .'</option>';
    }

    /**
     * Устанавливает флаг для элемента.
     * @param $value bool - флаг элемента
     * @return bool
     */
    public function setSelected($value)
    {
        if (!is_bool($value))
        {
            return false;
        }
        $this->selected = $value;
    }

    /**
     * Возвращает значение флага элемента.
     * @return bool
     */
    public function getSelected()
    {
        return $this->selected;
    }
}