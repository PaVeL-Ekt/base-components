<?php

namespace PavelEkt\BaseComponents\Filters;

use PavelEkt\BaseComponents\Abstracts\BaseFilter;
use PavelEkt\BaseComponents\Validators\EmailValidator;

/**
 * Class EmailFilter
 * Фильтр, для строк c EMail.
 * @package PavelEkt\BaseComponents\Filters
 * @property string $default    EMail по умолчанию.
 */
class EmailFilter extends BaseFilter
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
            if (!EmailValidator::isValid($default)) {
                $result = $default;
            } else {
                $result = $default;
            }
        } else {
            if (!EmailValidator::isValid($value)) {
                $default = $this->default;
                if (EmailValidator::isValid($default)) {
                    $result = $default;
                }
            } else {
                $result = $value;
            }
        }
        return $result;
    }
}
