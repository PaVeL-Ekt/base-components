<?php

namespace PavelEkt\BaseComponents\Validators;

use PavelEkt\BaseComponents\Abstracts\BaseValidator;

/**
  * Валидация по регулярному вырожению.
 * @package PavelEkt\BaseComponents\Validators
 */
class RegexpValidator extends BaseValidator
{
    public function attributes()
    {
        return [
            'regexp'
        ];
    }

    public function isValid($text)
    {
        if (!empty($text)) {
            return (bool) preg_match($this->_getAttribute(null, 'regexp'), $text, $matches);
        }
        return false;
    }
}
