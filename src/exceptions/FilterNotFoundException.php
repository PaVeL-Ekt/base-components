<?php

namespace PavelEkt\BaseComponents\Exceptions;

use PavelEkt\BaseComponents\Abstracts\BaseException;

/**
 * Class FilterNotFoundException
 * Исключение, вызываемое, если не найден класс фильтра.
 * @package PavelEkt\BaseComponents\Exceptions
 */
class FilterNotFoundException extends BaseException
{
    public function __construct($filterName, $previous = null)
    {
        parent::__construct(
            'Filter \'' . $filterName . '\' not found.',
            1015,
            $previous
        );
    }
}
