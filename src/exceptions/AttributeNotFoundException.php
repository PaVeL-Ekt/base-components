<?php

namespace PavelEkt\BaseComponents\Exceptions;

class AttributeNotFoundException extends \Exception
{
    const CODE_ATTRIBUTE_NOT_FOUND = 1001;
    /**
     * @param string|object $class Object or className where attribute not found.
     * @param string $attrName Not founded attribute name.
     * @param int $code [optional] The Exception code.
     * @param \Exception $previous [optional] The previous exception used for the exception chaining.
     */
    public function __construct($class, $attrName, $code = self::CODE_ATTRIBUTE_NOT_FOUND, $previous = null)
    {
        $message = 'Attribute `' . $attrName . '` not found in class ' .
            (is_object($class) ? get_class($class) : $class);
        parent::__construct($message, $code, $previous);
    }
}
