<?php

namespace PavelEkt\BaseComponents\Abstracts;

use PavelEkt\BaseComponents\Interfaces\ValidatorInterface;

class BaseValidator extends BaseObject implements ValidatorInterface
{
    public function isValid($value)
    {
        return $value;
    }
}
