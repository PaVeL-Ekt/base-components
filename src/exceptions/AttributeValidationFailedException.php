<?php

namespace PavelEkt\BaseComponents\Exceptions;

use PavelEkt\BaseComponents\Abstracts\BaseException;

class AttributeValidationFailedException extends BaseException
{
    public function __construct($attribute, $class = null, $previous = null)
    {
        parent::__construct(
            'The attribute \'' . $attribute . '\' validation failed in class \'' . $this->getExceptionClass($class) . '\'.',
            1013,
            $previous
        );
    }
}
