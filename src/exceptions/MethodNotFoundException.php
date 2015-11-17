<?php

namespace PavelEkt\BaseComponents\Exceptions;

use PavelEkt\BaseComponents\Abstracts\BaseMessageException;

class MethodNotFoundException extends BaseMessageException
{
    const EXCEPTION_CODE = 1002;
    const EXCEPTION_MESSAGE = 'Method \'{{method}}\' not found in class \'{{class}}\'.';

    /**
     * @inheritdoc
     * $params must contains 'method' - method name and 'class' caller class or class name.
     */
    public function __construct($params, $previous = null)
    {
        if (!empty($params['class'])) {
            if (is_object($params['class'])) {
                $params['class'] = get_class($params['class']);
            }
        }
        if (empty($params['class'])) {
            $stackTrace = debug_backtrace(!DEBUG_BACKTRACE_PROVIDE_OBJECT && DEBUG_BACKTRACE_IGNORE_ARGS, 0);
            $params['class'] = $stackTrace[count($stackTrace) - 1]['class'];
        }
        parent::__construct($params, $previous);
    }
}
