<?php
function recordCount($r_count = 0, $r_show = 0, $text = null)
{
    $result = '<caption align="bottom">';
    $r_count = intval($r_count);
    $r_show = intval($r_show);

    if (!empty($text))
    {
        $result .= $text.' '. $r_show . ' из '. $r_count;
    }
    else
    {
        $result .= 'Количество записей: '. $r_show . ' из '. $r_count;
    }
    $result .= '</caption>';
    return $result;
}