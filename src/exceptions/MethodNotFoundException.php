<?php

namespace PavelEkt\BaseComponents\Exceptions;

class MethodNotFoundException extends \Exception
{
    const CODE_METHOD_NOT_FOUND = 1002;
    /**
     * @param string|object $class Object or className where method not found.
     * @param string $methodName Not founded method name.
     * @param int $code [optional] The Exception code.
     * @param \Exception $previous [optional] The previous exception used for the exception chaining.
     */
    public function __construct($class, $methodName, $code = self::CODE_METHOD_NOT_FOUND, $previous = null)
    {
        $message = 'Method `' . $methodName . '` not found in class ' .
            (is_object($class) ? get_class($class) : $class);
        parent::__construct($message, $code, $previous);
    }
}
