<?php

namespace PavelEkt\BaseComponents\Filters;

use PavelEkt\BaseComponents\Abstracts\BaseFilter;

/**
 * Class DefaultFilter
 * Фильтр, добавляющий параметру значение по умолчанию.
 * @package PavelEkt\BaseComponents\Filters
 */
class DefaultFilter extends BaseFilter
{
    /**
     * @inheritdoc
     */
    public function attributes() {
        return [
            'default'
        ];
    }

    /**
     * @inheritdoc
     */
    public function filter($value = null)
    {
        if (is_null($value)) {
            return $this->_getAttribute('default');
        }
        return $value;
    }
}
