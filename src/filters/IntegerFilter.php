<?php

namespace PavelEkt\BaseComponents\Filters;

use PavelEkt\BaseComponents\Abstracts\BaseFilter;
use PavelEkt\BaseComponents\Helpers\IntegerHelper;
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
        if (is_null($value)) {
            $value = $this->default;
        }
        $value = IntegerHelper::toInt($value);

        $min = $this->min;
        if (!is_null($min) && $value < IntegerHelper::toInt($min)) {
            $value = $min;
        }

        $max = $this->max;
        if (!is_null($max) && $value > IntegerHelper::toInt($max)) {
            $value = $max;
        }

        return $value;
    }
}
