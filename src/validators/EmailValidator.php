<?php

namespace PavelEkt\BaseComponents\Validators;

use PavelEkt\BaseComponents\Abstracts\BaseValidator;

/**
 * Class Email
 * Валидация E-Mail
 * @package PavelEkt\BaseComponents\Validators
 */
class EmailValidator extends BaseValidator
{
    public function isValid($email)
    {
        return !(empty($email) || filter_var($email, FILTER_VALIDATE_EMAIL) === false);
    }
}
