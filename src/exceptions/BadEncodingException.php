<?php

namespace PavelEkt\BaseComponents\Exceptions;

use PavelEkt\BaseComponents\Abstracts\BaseException;

class BadEncodingException extends BaseException
{
    public function __construct($encoding, $previous = null)
    {
        parent::__construct(
            'Trying to use unsupported encoding \'' . $encoding . '\'.',
            1003,
            $previous
        );
    }
}
