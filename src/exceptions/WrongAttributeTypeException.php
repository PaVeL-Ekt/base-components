<?php

namespace PavelEkt\BaseComponents\Exceptions;

use PavelEkt\BaseComponents\Abstracts\BaseException;

class WrongAttributeTypeException extends BaseException
{
    public function __construct($attributeName, $expectedType, $givenType, $previous = null)
    {
        parent::__construct(
            'Type of attribute \'' . $attributeName . '\' expected \'' . $expectedType . '\' but \'' . $givenType . '\' given.',
            1012,
            $previous
        );
    }
}
