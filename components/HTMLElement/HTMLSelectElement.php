<?php

namespace HTMLElement;
include 'HTMLSelectOptionElement.php';
include 'HTMLSelectOptgroupElement.php';

use HTMLElement\HTMLSelectOptgroupElement;
use HTMLElement\HTMLSelectOptionElement;

class HTMLSelectElement extends HTMLElementBase
{
    /*******************************************************
     ********************* Поля класса *********************
     *******************************************************/

    // Установки конфигурации элемента
    const HTML_E_CONFIG_VALUE_NAME = 'value';

    /**
     * Обязательный ли к выбору элемент.
     * @var bool
     */
    private $necessarily = false;

    /*******************************************************
     ******************** Методы класса ********************
     *******************************************************/

    /**
     * Устанавливает обязательно ли выбирать элемент.
     * @param $value bool
     */
    public function setNecessarily($value)
    {
        if (is_bool($value))
        {
            $this->necessarily = $value;
        }
    }

    /**
     * Возвращает обязатнольно ли выбирать элемент.
     * @return bool
     */
    public function getNecessarily()
    {
        return $this->necessarily;
    }

    /**
     * @param $options
     * @param $optgroups
     * @return string
     */
    public function render(&$options, &$optgroups)
    {
        $default_group = null;
        $result = '';
        $el_attributes = '';

        for ($i = 0; $i < count($options); $i++)
        {
            if ($options[$i]->getGroup() !== null)
            {
                if ($default_group !== $options[$i]->getGroup())
                {
                    if ($default_group !== null)
                    {
                        $result .= HTMLSelectOptgroupElement::renderClose();
                    }
                    $result .= $optgroups[$options[$i]->getGroup()]->renderOpen();
                    $default_group = $options[$i]->getGroup();
                }
            }
            else
            {
                if ($default_group !== null)
                {
                    $result .= HTMLSelectOptgroupElement::renderClose();
                    $default_group = null;
                }
            }

            $result .= $options[$i]->render();
        }
        if ($default_group !== null)
        {
            $result .= HTMLSelectOptgroupElement::renderClose();
            $default_group = null;
        }

        $full_config = parent::getFullConfig();

        foreach ($full_config as $key => $val)
        {
            if ($val !== false)
            {
                if ($el_attributes != '') $el_attributes .= ' ';

                if ($key != self::HTML_E_CONFIG_VALUE_NAME) $el_attributes .= $key .'="'.$val.'"';
            }
        }

        return ((parent::getCaption() != '')
            ? '<label'.
            ((parent::getId() != '' && parent::getId() != false)
            ? ' for="'. parent::getId().'"'
            : '').'>'.$this->getCaption().':'
            . (($this->getNecessarily() !== false)? ' *':'').'</label><br>'
            :'')
            .'<select '. $el_attributes .'>'.$result.'</select>';
    }

}