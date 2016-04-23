<?php

namespace PavelEkt\BaseComponents\Filters;

use PavelEkt\BaseComponents\Abstracts\BaseFilter;
use PavelEkt\BaseComponents\Helpers\StringHelper;

/**
 * Class BooleanFilter
 * Фильтр, булевых значений.
 * @package PavelEkt\BaseComponents\Filters
 * @property boolean    $default    Значение по умолчанию.
 */
class BooleanFilter extends BaseFilter
{
    protected $_attributes = [
        'default'   => false,
    ];

    /**
     * @param mixed $value
     * @return bool
     */
    public function filter($value)
    {
        if (!is_bool($value)) {
            if (is_scalar($value)) {
                if (is_string($value) && $value == 'false') {
                    $value = false;
                } else {
                    $value = boolval($value);
                }
            } else {
                $default = $this->default;
                if (!is_bool($default)) {
                    $default = false;
                }
                $value = $default;
            }
        }
        return $value;
    }
}
