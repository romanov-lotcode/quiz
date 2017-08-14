<?php

namespace HTMLElement;


class HTMLTextTextareaElement extends HTMLTextElement
{
    /**
     * Проводит необходимую проверку для текущего типа.
     */
    public function check()
    {
        if (parent::getMin() !== false)
        {
            if (strlen(parent::getValue()) < parent::getMin())
            {
                parent::setCheck(false);
                return;
            }
        }
        if (parent::getMax() !== false)
        {
            if (strlen(parent::getValue()) > parent::getMax())
            {
                parent::setCheck(false);
                return;
            }
        }
    }

    /**
     * Отрисовывает html элемент.
     * @return string
     */
    public function render()
    {
        $el_attributes = '';

        //parent::setStyle('width: 250px;');
        if (parent::getCheck() === false)
        {
            parent::addStyleClass('uk-form-danger');
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
            . (($this->getMin() !== false)? ' *':'').'</label><br>'
            :'')
        . '<textarea '
        .$el_attributes
        . (($this->getDisabled() === true)? 'disabled ' : '')
        .'>' . parent::getValue() . '</textarea>';
    }
}