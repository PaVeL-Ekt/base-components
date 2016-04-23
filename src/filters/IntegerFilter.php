<?php

namespace PavelEkt\BaseComponents\Filters;

use PavelEkt\BaseComponents\Abstracts\BaseFilter;
use PavelEkt\BaseComponents\Helpers\StringHelper;

/**
 * Class IntegerFilter
 * Фильтр, целых чисел.
 * @package PavelEkt\BaseComponents\Filters
 * @property string $default    Значение по умолчанию.
 * @property int    $min        Минимильное значение.
 * @property int    $max        Максимальное значение.
 */
class IntegerFilter extends BaseFilter
{
    protected $_attributes = [
        'default'   => 0,
        'min' => null,
        'max' => null,
    ];

    /**
     * @param mixed $value
     * @return int
     */
    public function filter($value)
    {
        if (!is_int($value)) {
            if (is_scalar($value)) {
                $value = intval($value);
            } else {
                $default = $this->default;
                if (!is_int($default)) {
                    $default = 0;
                }
                $value = $default;
            }
        }

        $min = $this->min;
        if (!is_null($min) && is_int($min) && $value < $min) {
            $value = $min;
        }

        $max = $this->max;
        if (!is_null($max) && is_int($max) && $value > $max) {
            $value = $max;
        }

        return $value;
    }
}
