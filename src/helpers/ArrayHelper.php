<?php

namespace PavelEkt\BaseComponents\Helpers;

class ArrayHelper
{
    /**
     * Приведение к Array
     * @param mixed $value Значение, которое необходимо привести к Array
     * @return array
     */
    public static function toArray($value)
    {
        if (!is_array($value)) {
            if (!settype($value, 'array')) {
                $value = [];
            }
        }
        return $value;
    }
}
