<?php
function renderDescriptionDatetimeFIO($arr_value)
{
    $date_converter = new Date_Converter();
    $result = '';
    if (is_array($arr_value) && count($arr_value) > 0)
    {
        $result .= '<div class="uk-form-row uk-width-1-1" align="right">
                        <span class="description_light">
                            Последняя редакция: '. $date_converter->datetimeToString($arr_value['change_datetime']).'
                        </span><br />
                        <span class="description_light">
                            '.trim($arr_value['lastname'] . ' ' . $arr_value['firstname'] . ' ' . $arr_value['middlename']).'
                        </span>
                    </div>';
    }
    return $result;
}