<?php

namespace PavelEkt\BaseComponents\Validators;

use PavelEkt\BaseComponents\Interfaces\ValidatorInterface;

/**
 * Class Email
 * Валидация E-Mail
 * @package PavelEkt\BaseComponents\Validators
 */
class EmailValidator implements ValidatorInterface
{
    public static function isValid($email)
    {
        return !(empty($email) || filter_var($email, FILTER_VALIDATE_EMAIL) === false);
    }
}
