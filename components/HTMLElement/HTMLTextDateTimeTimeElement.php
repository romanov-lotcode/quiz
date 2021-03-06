<?php
/**
 * Created by PhpStorm.
 * User: romanovss
 * Date: 14.04.2017
 * Time: 11:26
 */

namespace HTMLElement;


class HTMLTextDateTimeTimeElement extends HTMLTextDateTimeElement
{

    /**
     * Отрисовывает html элемент.
     * @return string
     */
    public function render()
    {
        $el_attributes = '';

        $el_attributes .= ' type="text"';
        parent::setStyle('width: 250px;');
        if (parent::getCheck() === false)
        {
            $this->setStyle('border: 1px solid red;');
        }

        $full_config = parent::getFullConfig();

        foreach ($full_config as $key => $val)
        {
            if ($val !== false)
            {
                if ($el_attributes != '') $el_attributes .= ' ';

                $el_attributes .= $key .'="'.$val.'"';
            }
        }

        return ((parent::getCaption() != '')
            ? '<label'.
            ((parent::getId() != '' && parent::getId() != false)
                ? ' for="'. parent::getId().'"'
                : '').'>'.$this->getCaption().':'
            . ((parent::getMin() !== false)? ' *':'').'</label><br>'
            :'')
        . '<input '
        .$el_attributes
        . (($this->getDisabled() === true)? 'disabled ' : '')
        .'  />';
    }
}