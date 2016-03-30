<?php

namespace PavelEkt\BaseComponents\Exceptions;

use PavelEkt\BaseComponents\Abstracts\BaseMessageException;

class WrongParameterTypeException extends BaseMessageException
{
    const EXCEPTION_CODE = 1004;
    const EXCEPTION_MESSAGE = 'Type of parameter \'{{paramName}}\' expected \'{{expectType}}\' but \'{{givenType}}\' given.';
}
