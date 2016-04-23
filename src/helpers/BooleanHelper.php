<?php

namespace PavelEkt\BaseComponents\Helpers;

class BooleanHelper
{
    /**
     * Приведение к Boolean
     * @param mixed $value Значение, которое необходимо привести к Boolean
     * @return bool
     */
    public static function toBool($value)
    {
        if (!is_bool($value)) {
            if (is_scalar($value) && $value == 'false') {
                $value = false;
            } else {
                if (!settype($value, 'bool')) {
                    $value = false;
                }
            }
        }
        return $value;
    }
}
