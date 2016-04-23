<?php

namespace PavelEkt\BaseComponents\Filters;

use PavelEkt\BaseComponents\Abstracts\BaseFilter;
use PavelEkt\BaseComponents\Helpers\StringHelper;

/**
 * Class RegexFilter
 * Фильтр, для текстовых строк, с применением регулярного выражения.
 * @package PavelEkt\BaseComponents\Filters
 * @property string $pattern     Регулярное выражение для замены текста.
 * @property string $replacement Текст на который заменяем шаблон.
 */
class RegexpFilter extends StringFilter
{
    public function extendedAttributes()
    {
        return [
            'pattern',
            'replacement'
        ];
    }

    public function filter($value)
    {
        if (!is_string($value)) {
            $value = StringHelper::toStr($value);
        }
        $pattern = StringHelper::toStr($this->pattern);
        $replacement = StringHelper::toStr($this->replacement);
        if (is_string($pattern) && !empty($pattern) && !empty($replacement)) {
            $value = preg_replace($pattern, $replacement, $value);
        }
        return parent::filter($value);
    }
}
