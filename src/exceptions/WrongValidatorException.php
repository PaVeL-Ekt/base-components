<?php

namespace PavelEkt\BaseComponents\Exceptions;

use PavelEkt\BaseComponents\Abstracts\BaseException;
use PavelEkt\BaseComponents\Helpers\StringHelper;

/**
 * Class WrongValidatorException
 * Исключение, вызываемое, если указан неверный валидатор.
 * @package PavelEkt\BaseComponents\Exceptions
 */
class WrongValidatorException extends BaseException
{
    /**
     * @param string $attributeName Attribute name
     * @param mixed $validator        Validator
     * @param null $previous
     */
    public function __construct($attributeName, $validator, $previous = null)
    {
//        $filter = StringHelper::strCrop(StringHelper::printVar($validator), 50);
        parent::__construct(
            'Validator \'' . $validator . '\' for attribute \'' . $attributeName . '\' is wrong.',
            1018,
            $previous
        );
    }
}
