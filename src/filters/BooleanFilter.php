<?php

namespace PavelEkt\BaseComponents\Filters;

use PavelEkt\BaseComponents\Abstracts\BaseFilter;
use PavelEkt\BaseComponents\Helpers\BooleanHelper;
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
        if (is_null($value)) {
            $value = $this->default;
        }
        return BooleanHelper::toBool($value);
    }
}
