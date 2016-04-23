<?php

namespace PavelEkt\BaseComponents\Helpers;

class FloatHelper
{
    /**
     * Приведение к Float
     * @param mixed $value Значение, которое необходимо привести к float
     * @return float
     */
    public static function toFloat($value)
    {
        if (!is_float($value)) {
            if (!settype($value, 'float')) {
                $value = 0.0;
            }
        }
        return $value;
    }
}
