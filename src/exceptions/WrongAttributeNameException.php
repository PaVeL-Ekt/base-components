<?php

namespace PavelEkt\BaseComponents\Exceptions;

use PavelEkt\BaseComponents\Abstracts\BaseException;

class WrongAttributeNameException extends BaseException
{
    public function __construct($attributeName, $previous = null)
    {
        parent::__construct(
            'Incorrect attribute name \'' . $attributeName . '\'.',
            1011,
            $previous
        );
    }
}
