<?php

/**
 * Class App_Validate необходим для проверок.
 */
class App_Validate
{
    public function checkLogin($value)
    {
        $pattern = '/^[a-zA-Z][a-zA-Z0-9-_\.]{5,32}$/';
        if (preg_match($pattern, $value))
        {
            return true;
        }
        return false;
    }

    /**
     * Првоерка пароля
     * @param string $value - пароль
     * @return bool
     */
    public function checkPassword($value)
    {
        if (strlen($value) >= 6 && strlen($value) <= 40)
        {
            return true;
        }
        return false;
    }

    /**
     * Проверить число
     * @param int $value - Значение числа
     * @param bool|true $canzero - Флаг: может ли быть 0
     * @param bool|true $limit - Флаг: есть ли лимит
     * @param int $min - Минимальное значение лимита
     * @param int $max - Максимальное значение лимита
     * @return bool
     */
    public function checkInt($value, $canzero = true, $limit = true, $min = 0, $max = 10)
    {
        if ($value != null)
        {
            if ($canzero)
            {
                if ($limit)
                {
                    if ($value >= 0 || $value <= $max)
                    {
                        return true;
                    }
                }
                else
                {
                    if ($value == 0)
                    {
                        return true;
                    }
                }
            }
            if(filter_var($value, FILTER_VALIDATE_INT))
            {
                if ($limit)
                {
                    if ($value < $min || $value > $max)
                    {
                        return false;
                    }
                    else
                    {
                        return true;
                    }
                }
                else
                {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Проверяет кооректно ли введен e-mail
     * @param string $value - e-mail
     * @return bool
     */
    public function checkEmail($value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL) !== false)
        {
            return true;
        }
        return false;
    }

    /**
     * Получить значение времени по формату
     * @param [] $time - время
     * @param string $format - формат времени
     * @return bool|string
     */
    public function getTimeFromArrayInt($time, $format = 'H:i:s')
    {
        $time_string = '';
        if (!is_array($time))
        {
            return false;
        }
        if ($time['hour'] == null || $time['minute'] == null || $time['second'] == null)
        {
            return false;
        }
        if ($format == 'H:i:s')
        {
            if (strlen($time['hour']) == 1)
            {
                $time['hour'] = '0'.$time['hour'];
            }
            if (strlen($time['minute']) == 1)
            {
                $time['minute'] = '0'.$time['minute'];
            }
            if (strlen($time['second']) == 1)
            {
                $time['second'] = '0'.$time['second'];
            }
            $time_string = $time['hour'] . ':'
                . $time['minute'] . ':' . $time['second'];
        }

        return $time_string;
    }

    /**
     * Получить значение времени по формату в виде массива
     * @param string $time_string - время
     * @param string $format - формат времени
     * @return array
     */
    public function setTimeArrayFromTime($time_string, $format = 'H:i:s')
    {
        $time['hour'] = '0';
        $time['minute'] = '0';
        $time['second'] = '0';

        if ($format == 'H:i:s')
        {
            $segments = explode(":", $time_string);
            if(count($segments) == 3)
            {
                $time['hour'] = $segments[0];
                $time['minute'] = $segments[1];
                $time['second'] = $segments[2];
            }
        }
        return $time;
    }

    /**
     * Возвращает строку с переводом первый символ в верхний регистр
     * @param string $string - строка, которую нужно преобразовать
     * @param string $enc - кодировка
     * @return string
     */
    private function my_ucfirst($string, $enc = DEFAULT_ENCODING_LOWERCASE)
    {
        return mb_strtoupper(mb_substr($string, 0, 1, $enc), $enc) .
        mb_substr($string, 1, mb_strlen($string, $enc), $enc);
    }

    /**
     * Возвращает строку с переводом всех первых символов в верхний регистр
     * @param string $string - строка, которую нужно преобразовать
     * @return string
     */
    public function my_ucwords($string)
    {
        $segments = explode(" ", $string);
        $newString = "";
        foreach($segments as $key => $value)
        {
            $newString .= " " .$this->my_ucfirst(mb_strtolower($value, DEFAULT_ENCODING_LOWERCASE));
        }
        return $newString;
    }
}