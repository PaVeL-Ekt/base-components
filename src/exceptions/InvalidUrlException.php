<?php

namespace PavelEkt\BaseComponents\exceptions;

use PavelEkt\BaseComponents\Abstracts\BaseException;

class InvalidUrlException extends BaseException
{
    public function __construct($url, $previous = null)
    {
        parent::__construct(
            'Url \'' . $url . '\' is not valid.',
            1101,
            $previous
        );
    }
}
