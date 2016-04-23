<?php

namespace PavelEkt\BaseComponents\Filters;

use PavelEkt\BaseComponents\Abstracts\BaseFilter;
use PavelEkt\BaseComponents\Helpers\StringHelper;

/**
 * Class FloatFilter
 * Фильтр, чисел с плавающей точкой.
 * @package PavelEkt\BaseComponents\Filters
 * @property float  $default    Значение по умолчанию.
 * @property float  $min        Минимильное значение.
 * @property float  $max        Максимальное значение.
 */
class FloatFilter extends BaseFilter
{
    protected $_attributes = [
        'default'   => 0,
        'min' => null,
        'max' => null,
    ];

    /**
     * @inheritdoc
     * @param mixed $value
     * @return float
     */
    public function filter($value)
    {
        if (!is_float($value)) {
            if (is_scalar($value)) {
                $value = floatval($value);
            } else {
                $default = $this->default;
                if (!is_float($default)) {
                    $default = 0;
                }
                $value = $default;
            }
        }

        $min = $this->min;
        if (!is_null($min) && is_float($min) && $value < $min) {
            $value = $min;
        }

        $max = $this->max;
        if (!is_null($max) && is_float($max) && $value > $max) {
            $value = $max;
        }

        return $value;
    }
}
