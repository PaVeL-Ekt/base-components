<?php

namespace PavelEkt\BaseComponents\Filters;

use PavelEkt\BaseComponents\Abstracts\BaseFilter;
use PavelEkt\BaseComponents\Validators\UrlValidator;

/**
 * Class UrlFilter
 * Фильтр, для строк с URL.
 * @package PavelEkt\BaseComponents\Filters
 * @property string $default    Url по умолчанию.
 */
class UrlFilter extends BaseFilter
{
    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            'default' => null,
        ];
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function filter($value)
    {
        $result = null;
        if (is_null($value)) {
            $default = $this->default;
            if (!UrlValidator::isValid($default)) {
                $result = $default;
            } else {
                $result = $default;
            }
        } else {
            if (!UrlValidator::isValid($value)) {
                $default = $this->default;
                if (UrlValidator::isValid($default)) {
                    $result = $default;
                }
            } else {
                $result = $value;
            }
        }
        return $result;
    }
}
