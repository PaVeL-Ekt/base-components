<?php

namespace PavelEkt\BaseComponents\Exceptions;

use PavelEkt\BaseComponents\Abstracts\BaseException;

class AttributeNotFoundException extends BaseException
{
    public function __construct($attribute, $class = null, $previous = null)
    {
        parent::__construct(
            'Attribute \'' . $attribute . '\' not found in class \'' . $this->getExceptionClass($class) . '\'.',
            1001,
            $previous
        );
    }
}
