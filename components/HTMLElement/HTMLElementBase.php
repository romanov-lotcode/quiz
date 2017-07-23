<?php

namespace HTMLElement;

/**
 * Устанавливает конфигурацию для объекта, который
 * указан в первом входном параметре.
 * @param $data object - объект
 * @param $key string - ключ
 * @param $value string - значение
 * @return bool
 */
function __setConfig(&$data, $key, $value)
{
    if (empty($key))
    {
        return false;
    }
    $data[$key] = $value;
}


class HTMLElementBase
{
    /*******************************************************
     ********************* Поля класса *********************
     *******************************************************/

    private $default_charset = 'UTF-8';

    // Установки конфигурации элемента
    const HTML_E_CONFIG_NAME_NAME = 'name';
    const HTML_E_CONFIG_STYLE_NAME = 'style';
    const HTML_E_CONFIG_ID_NAME = 'id';
    const HTML_E_CONFIG_CLASS_NAME = 'class';

    /*
     * Для проверки элемента. Если поле меняет состояние
     * на false, значит данные не проходят проверку.
     * Состояние true - данные проходят проверку.
     */
    private $check = true;

    /*
     * Заголовок. Может применятся в <label />.
     */
    private $caption = false;

    /**
     * Флаг для включения/выключения элемента.
     * false - элемент включен.
     * true - элемент выключен.
     * @var bool
     */
    private $disabled = false;

    /**
     * Текущее поле хранит в себе конфигурацию
     * HTML элемента (набор атрибутов) с их значением
     * в паре ключ=>значение.
     * Пример: name='someName', value='someValue' и т.п.
     * @var array
     */
    private $element_config = [];

    /*******************************************************
     ******************** Методы класса ********************
     *******************************************************/

    /**
     * Возвращает кодировку по умолчанию.
     * @return string
     */
    public function getDefaultCharset()
    {
        return $this->default_charset;
    }

    /**
     * Устанавливает значение для заголовка.
     * @param $value string - значение заголовка
     * @return bool
     */
    public function setCaption($value)
    {
        if (empty($value))
        {
            return false;
        }
        $this->caption = $value;
    }

    /**
     * Возвращает заголовок элемента.
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }


    /*
    * *******************************************************
    * Установка и получение значений элемента. Работа с атрибутами
    * *******************************************************
    */

    /**
     * Устанавливает значение атрибута элемента.
     * @param $key string - ключ
     * @param $value - значение
     * @return bool
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
     * Устанавливает имя элемента.
     * @param $value string - значение
     */
    public function setName($value)
    {
        $this->setConfig(self::HTML_E_CONFIG_NAME_NAME, trim($value));
    }

    /**
     * Возвращает имя элемента.
     * @return bool OR string
     */
    public function getName()
    {
        return $this->getConfig(self::HTML_E_CONFIG_NAME_NAME);
    }

    /**
     * Устанавливает ID элемента.
     * @param $value string - значение
     */
    public function setId($value)
    {
        $this->setConfig(self::HTML_E_CONFIG_ID_NAME, trim($value));
    }

    /**
     * Возвращает ID элемента.
     * @return bool OR string
     */
    public function getId()
    {
        return $this->getConfig(self::HTML_E_CONFIG_ID_NAME);
    }

    /**
     * Устанавилвает значение для видимости элемента.
     * @param $value bool - true - не показывать / false - показывать
     * @return bool
     */
    public function setDisabled($value)
    {
        if (!is_bool($value))
        {
            return false;
        }
        $this->disabled = $value;
    }

    /**
     * Возвращает значение видимости.
     * @return bool
     */
    public function getDisabled()
    {
        return $this->disabled;
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

    /*
     * ДАННЫЙ МЕТОД НЕ РЕАЛИЗОВАН.
     * ЗАДУМКА ТАКАЯ: ЕСЛИ НАЙДЕНО ТОЧНОЕ СОВПАДЕНИЕ,
     * ТО УДАЛИ ЕГО!
     */
    /*public function removeStyleClass($value)
    {
        if (empty($value))
        {
            return false;
        }
    }*/

    /**
     * Устанавливает значение проверки.
     * @param $value bool - значение проверки
     * @return bool
     */
    public function setCheck($value)
    {
        if (!is_bool($value))
        {
            return false;
        }
        $this->check = $value;
    }

    /**
     * Возвращает значение проверки.
     * @return bool
     */
    public function getCheck()
    {
        return $this->check;
    }

    /**
     * Возвращает шаблон, если нет элемента.
     * @return string
     */
    public function getNoElement()
    {
        return '<span>No element</span>';
    }
}