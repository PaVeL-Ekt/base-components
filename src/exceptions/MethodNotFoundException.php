<?php

namespace PavelEkt\BaseComponents\Exceptions;

use PavelEkt\BaseComponents\Abstracts\BaseException;

class MethodNotFoundException extends BaseException
{
    public function __construct($method, $class = null, $previous = null)
    {
        parent::__construct(
            'Method \'' . $method . '\' not found in class \'' . $this->getExceptionClass($class) . '\'.',
            1002,
            $previous
        );
    }
}
