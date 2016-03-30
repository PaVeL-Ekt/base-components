<?php

namespace PavelEkt\BaseComponents\Abstracts;

abstract class BaseMessageException extends BaseTemplateException
{
    /**
     * @const int EXCEPTION_CODE The Exception code.
     */
    const EXCEPTION_CODE = 9999;
    /**
     * @const string EXCEPTION_MESSAGE The Exception message.
     */
    const EXCEPTION_MESSAGE = 'Unknown application error in class {{class}}.';

    public function __construct($params = [], $previous = null)
    {
        parent::__construct(
            $this->generateExceptionMessage(static::EXCEPTION_MESSAGE, $params),
            static::EXCEPTION_CODE,
            $previous
        );
    }
}
