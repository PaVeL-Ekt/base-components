<?php

namespace PavelEkt\BaseComponents\Abstracts;

abstract class BaseTemplateMessageException extends BaseTemplateException
{
    public function __construct($template = 'Unknown application error in class {{class}}.', $params = [], $code = 9998, $previous = null)
    {
        parent::__construct(
            $this->generateExceptionMessage($template, $params),
            $code,
            $previous
        );
    }
}
