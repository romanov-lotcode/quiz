<?php

namespace HTMLElement;


class HTMLCheckboxAndRadioRadioElement extends HTMLCheckboxAndRadio
{
    /**
     * Отрисовывает html элемент.
     * @return string
     */
    public function render()
    {
        $el_attributes = '';

        if (parent::getCaption() != false)
        {
            $el_attributes .= ((!parent::getConfig('type'))?' type="radio"': '');

            $full_config = parent::getFullConfig();

            foreach ($full_config as $key => $val)
            {
                if ($val !== false)
                {
                    if ($el_attributes != '') $el_attributes .= ' ';

                    $el_attributes .= $key .'="'.$val.'"';
                }
            }

            if (parent::getChecked() === true)
            {
                $el_attributes .= ' checked';
            }

            return '<label'
            . ((parent::getId() != '' && parent::getId() != false)
                ? ' for="'.parent::getId().'"' : '')
            .'><input '. $el_attributes
            .((parent::getDisabled() === true)? ' disabled ' : '').'/>'
            . parent::getCaption() .'</label>';
        }
        else
        {
            return parent::getNoElement();
        }
    }
}