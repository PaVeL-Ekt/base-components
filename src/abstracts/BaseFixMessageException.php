<?php

namespace PavelEkt\BaseComponents\Abstracts;

abstract class BaseFixMessageException extends \Exception
{
    /**
     * @const int EXCEPTION_CODE The Exception code.
     */
    const EXCEPTION_CODE = 9999;
    /**
     * @const string EXCEPTION_MESSAGE The Exception message.
     */
    const EXCEPTION_MESSAGE = 'Unknown application error.';

    public function __construct($previous = null)
    {
        parent::__construct(static::EXCEPTION_MESSAGE, static::EXCEPTION_CODE, $previous);
    }
}