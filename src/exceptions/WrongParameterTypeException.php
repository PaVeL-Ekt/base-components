<?php

namespace PavelEkt\BaseComponents\Exceptions;

use PavelEkt\BaseComponents\Abstracts\BaseException;

class WrongParameterTypeException extends BaseException
{
    public function __construct($paramName, $expectedType, $givenType, $previous = null)
    {
        parent::__construct(
            'Type of parameter \'' . $paramName . '\' expected \'' . $expectedType . '\' but \'' . $givenType . '\' given.',
            1004,
            $previous
        );
    }
}
