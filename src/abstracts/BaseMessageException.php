<?php

namespace PavelEkt\BaseComponents\Abstracts;

abstract class BaseMessageException extends \Exception
{
    /**
     * @const int EXCEPTION_CODE The Exception code.
     */
    const EXCEPTION_CODE = 9999;
    /**
     * @const string EXCEPTION_MESSAGE The Exception message.
     */
    const EXCEPTION_MESSAGE = 'Unknown application error.';

    public function __construct($params, $previous = null)
    {
        $expressions = [];
        $replacements = [];
        if (is_array($params)) {
            foreach ($params as $key => $value) {
                $expressions[] = '/\{\{' . $key . '\}\}/uim';
                $replacements[] = $value;
            }
            $expressions[] = '/\{\{[a-z0-9-_]*\}\}/ium';
            $replacements[] = '';
        }
        $message = preg_replace($expressions, $replacements, static::EXCEPTION_MESSAGE);
        parent::__construct($message, static::EXCEPTION_CODE, $previous);
    }
}