<?php

namespace PavelEkt\BaseComponents\Helpers;

class IntegerHelper
{
    /**
     * Приведение к Integer
     * @param mixed $value Значение, которое необходимо привести к Integer
     * @return integer
     */
    public static function toInt($value)
    {
        if (!is_int($value)) {
            if (!settype($value, 'int')) {
                $value = 0;
            }
        }
        return $value;
    }
}
