<?php

namespace PavelEkt\BaseComponents\exceptions;

use PavelEkt\BaseComponents\Abstracts\BaseException;

class InvalidEmailException extends BaseException
{
    public function __construct($email, $previous = null)
    {
        parent::__construct(
            'E-Mail \'' . $email . '\' is not valid.',
            1102,
            $previous
        );
    }
}
