<?php

namespace PavelEkt\BaseComponents\Exceptions;

use PavelEkt\BaseComponents\Abstracts\BaseException;
use PavelEkt\BaseComponents\Helpers\StringHelper;

/**
 * Class WrongFilterException
 * Исключение, вызываемое, если указан неверный фильтр.
 * @package PavelEkt\BaseComponents\Exceptions
 */
class WrongFilterException extends BaseException
{
    public function __construct($attributeName, $filter, $previous = null)
    {
        $filter = StringHelper::strCrop(StringHelper::printVar($filter), 50);
        parent::__construct(
            'Filter \'' . $filter . '\' for attribute \'' . $attributeName . '\' is wrong.',
            1006,
            $previous
        );
    }
}
