<?php

namespace PavelEkt\BaseComponents\Filters;

use PavelEkt\BaseComponents\Abstracts\BaseFilter;
use PavelEkt\BaseComponents\Helpers\FloatHelper;
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
    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            'default' => 0,
            'min' => null,
            'max' => null,
        ];
    }

    /**
     * @inheritdoc
     * @return float
     */
    public function filter($value)
    {
        if (is_null($value)) {
            $value = $this->default;
        }
        $value = FloatHelper::toFloat($value);

        $min = $this->min;
        if (!is_null($min) && $value < FloatHelper::toFloat($min)) {
            $value = $min;
        }

        $max = $this->max;
        if (!is_null($max) && $value > FloatHelper::toFloat($max)) {
            $value = $max;
        }

        return $value;
    }
}
