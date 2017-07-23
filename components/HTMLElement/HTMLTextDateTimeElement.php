<?php

namespace HTMLElement;


class HTMLTextDateTimeElement extends HTMLTextElement
{
    /*******************************************************
     ********************* Поля класса *********************
     *******************************************************/

    /**
     * Формат даты и/или времени.
     * @var string
     */
    private $format = 'd.m.Y H:i:s';

    private $min = false;
    private $max = false;

    /*******************************************************
     ******************** Методы класса ********************
     *******************************************************/

    /**
     * Устаналвивает формат даты.
     * @param $value string - формат.
     * @return bool
     */
    public function setFormat($value)
    {
        if (empty($value))
        {
            return false;
        }
        $this->format = $value;
    }

    /**
     * Возвращает формат даты.
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Устанавливает минимальное значение даты и/или времени.
     * @param value $value string - $min
     * @return bool
     */
    public function setMin($value)
    {
        if (empty($value))
        {
            return false;
        }
        $this->min = $value;
    }

    /**
     * Возвращает минимальное значение даты и/или времени.
     * @return bool OR string
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * Устанавливает максимальное значение даты и/или времени.
     * @param value $value string - max.
     * @return bool
     */
    public function setMax($value)
    {
        if (empty($value))
        {
            return false;
        }
        $this->max = $value;
    }

    /**
     * Возвращает максимальное значение даты и/или времени.
     * @return bool OR string
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * Проводит необходимую проверку для текущего типа.
     * @return bool
     */
    public function check()
    {
        $is_date = $this->checkDateTime(parent::getValue(), $this->getFormat());

        if (!$is_date)
        {
            parent::setCheck(false);
            return false;
        }

        if ($this->getMin() !== false)
        {
            $min_check = $this->compareDateTime(parent::getValue(), $this->getMin(), $this->getFormat());
            if ($min_check === false || $min_check === (-1))
            {
                parent::setCheck(false);
                return false;
            }
        }

        if ($this->getMax() !== false)
        {
            $max_check = $this->compareDateTime(parent::getValue(), $this->getMax(), $this->getFormat());
            if ($max_check === false || $max_check === 1)
            {
                parent::setCheck(false);
                return false;
            }
        }
    }

    /**
     * Возвращает значение при сравнении даты и/или времени.
     * Возможные значения:
     * false - одно из значений не является датой и/или временем.
     * 0 - оба значения равны.
     * 1 - $value1 > $value2.
     * -1 - $value2 > $value1.
     * @param $value1 string - дата и/или время.
     * @param $value2 string - дата и/или время.
     * @param null $format string - формат.
     * @return bool|int
     */
    private function compareDateTime($value1, $value2, $format = null)
    {
        $format = (isset($format)) ? $format : $this->format;

        $datetime1 = \DateTime::createFromFormat($format, $value1);
        if ($datetime1 === false)
        {
            return false;
        }
        if ($datetime1->format($format) != $value1)
        {
            return false;
        }

        $datetime2 = \DateTime::createFromFormat($format, $value2);
        if ($datetime2 === false)
        {
            return false;
        }
        if ($datetime2->format($format) != $value2)
        {
            return false;
        }

        return ($datetime1 == $datetime2) ? 0 : (($datetime1 > $datetime2) ? 1 : -1);
    }

    /**
     * Возвращает состояние является ли значение датой и/или временем.
     * Возможные значения:
     * true - да.
     * false - нет.
     * @param $value string - дата и/или время
     * @param null $format string - формат.
     * @return bool
     */
    public function checkDateTime($value, $format = null)
    {
        $format = (isset($format)) ? $format : $this->getFormat();

        $datetime = \DateTime::createFromFormat($format, $value);
        if ($datetime === false)
        {
            return false;
        }
        return ($datetime->format($format) == $value) ? true : false;
    }
}