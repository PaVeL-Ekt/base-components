<?php

namespace PavelEkt\BaseComponents\Exceptions;

use PavelEkt\BaseComponents\Abstracts\BaseException;

class UnknownErrorException extends BaseException
{
    public function __construct($class = null, $previous = null)
    {
        parent::__construct(
            'Unknown application error in class \'' . $this->getExceptionClass($class) . '\'.',
            9999,
            $previous
        );
    }
}
