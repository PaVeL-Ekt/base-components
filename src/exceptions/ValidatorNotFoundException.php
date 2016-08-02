<?php

namespace PavelEkt\BaseComponents\Exceptions;

use PavelEkt\BaseComponents\Abstracts\BaseException;

/**
 * Class ValidatorNotFoundException
 * Исключение, вызываемое, если не найден класс валидатора.
 * @package PavelEkt\BaseComponents\Exceptions
 */
class ValidatorNotFoundException extends BaseException
{
    public function __construct($validatorName, $previous = null)
    {
        parent::__construct(
            'Validator \'' . $validatorName . '\' not found.',
            1017,
            $previous
        );
    }
}
