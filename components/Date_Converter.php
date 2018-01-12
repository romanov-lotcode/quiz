<?php

/*
 * Вспомогательный класс, для преобразований даты
 */
class Date_Converter
{
    /*
     * Преобразует дату в строку (гггг-мм-дд в дд.мм.гггг)
     * @var $date_to_convert date - дата
     * return string
     */
    public function dateToString($date_to_convert)
    {
        $segments = explode('-', $date_to_convert);
        if (count($segments) == 3)
        {
            $day = explode(' ', $segments[2]);
            if (count($day) == 2)
            {
                $segments[2] = $day[0];
            }
            return $segments[2].'.'.$segments[1].'.'.$segments[0];
        }
        return '00.00.0000';
    }

    /*
     * Преобразует дату и время в строку (гггг-мм-дд чч:мм:сс в дд.мм.гггг чч:мм:сс)
     * @var $datetime_to_convert datetime - дата и время
     * return string
     */
    public function datetimeToString($datetime_to_convert)
    {
        $segments = explode('-', $datetime_to_convert);
        if (count($segments) == 3)
        {
            $time = explode(' ', $segments[2]);
            if (count($time) == 2)
            {
                $segments[2] = $time[0];
            }
            return $segments[2].'.'.$segments[1].'.'.$segments[0].' '.$time[1];
        }
        return '00.00.0000 00:00:00';
    }

    /*
     * Преобразует дату и время в дату или время (дд.мм.гггг чч:мм:сс в дд.мм.гггг или чч:мм:сс)
     * @var $datetime datetime - дата и время
     * @var $param int - параметр (1 - Дата; 2 - Время)
     * return string
     */
    public function datetimeToDateOrTime($datetime, $param = 1)
    {
        if (empty($datetime)) return false;

        $segments = explode(' ', $datetime);
        if (count($segments) != 2) return false;

        if ($param == 2)
        {
            return $segments[1]; // Время
        }
        else
        {
            return $segments[0]; // Дата
        }
    }

    /*
     * Преобразует строку в дату (дд.мм.гггг в гггг-мм-дд)
     * @var $string_to_convert string - дата
     * return string OR boolean
     */
    public function stringToDate($string_to_convert)
    {
        $segments = explode('.', $string_to_convert);
        if (count($segments) == 3)
        {
            if (strlen($segments[0]) != 2 || (int)$segments[0] > 31 || $segments[0] < 1)
            {
                return false;
            }

            if (strlen($segments[1]) != 2 || $segments[1] > 12 || $segments[1] < 1)
            {
                return false;
            }

            if (strlen($segments[2]) != 4 || $segments[2] < 1000)
            {
                return false;
            }
            return $segments[2].'-'.$segments[1].'-'.$segments[0];
        }
        return false;
    }

    /*
     * Разбивает дату в массив (число, месяц, год)
     * @var $date string - дата
     * @var $param int - параметр, указывающий, в каком виде дата
     * (1 - ГГГГ-ММ-ДД; 2 - ДД.ММ.ГГГГ)
     * return array()
     */
    public function dateSplit($date, $param = 1)
    {
        $date_array['day'] = 0;
        $date_array['month'] = 0;
        $date_array['year'] = 0;

        if ($param == 2)
        {
            $segments = explode('.', $date);
            if (count($segments) == 3)
            {
                $date_array['day'] = (int)$segments[0];
                $date_array['month'] = (int)$segments[1];
                $date_array['year'] = (int)$segments[2];
            }
        }

        if ($param == 1)
        {
            $segments = explode('-', $date);
            if (count($segments) == 3)
            {
                $date_array['day'] = (int)$segments[2];
                $date_array['month'] = (int)$segments[1];
                $date_array['year'] = (int)$segments[0];
            }
        }

        return $date_array;
    }

    /**
     * Возвращает название месяца по его порядковому номеру
     * @param int $month - месяц
     * @param int $flag_value - параметр, указывающий в каком виде вернуть месяц
     * @return string
     */
    public function getMonthName($month, $flag_value = 1)
    {
        $result = '';

        $months_short = array( 1 => 'янв' , 'фев' , 'мар' , 'апр' , 'мая' , 'июн' , 'июл' , 'авг' , 'сен' , 'окт' , 'ноя' , 'дек');
        $months_rp_1 = array( 1 => 'Января' , 'Февраля' , 'Марта' , 'Апреля' , 'Мая' , 'Июня' , 'Июля' , 'Августа' , 'Сентября' , 'Октября' , 'Ноября' , 'Декабря');
        $months_rp_2 = array( 1 => 'января' , 'февраля' , 'марта' , 'апреля' , 'мая' , 'июня' , 'июля' , 'августа' , 'сентября' , 'октября' , 'ноября' , 'декабря');

        $flag_value = intval($flag_value);
        if ($flag_value <= 0)
        {
            $flag_value = 1;
        }

        $month = intval($month);

        if ($month == 0)
        {
            return $result;
        }

        if ($flag_value == 1)
        {
            $result = $months_short[$month];
        }
        if ($flag_value == 2)
        {
            $result = $months_rp_1[$month];
        }
        if ($flag_value == 3)
        {
            $result = $months_rp_2[$month];
        }

        return $result;
    }

    /**
     * Преобразует количество секунды в массив во время в виде массива
     * @param int $seconds - Секунды
     * @return mixed
     */
    public function secondsToTime($seconds)
    {
        $time['hours'] = 0;
        $time['minutes'] = 0;
        $time['seconds'] = 0;

        $seconds = intval($seconds);
        $time['seconds'] = $seconds % 60;
        $temp = floor($seconds / 60);
        $time['minutes'] = $temp % 60;
        $time['hours'] = floor($temp/60);

        return $time;
    }
}