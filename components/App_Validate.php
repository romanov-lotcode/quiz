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