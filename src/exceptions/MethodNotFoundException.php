<?php

namespace PavelEkt\BaseComponents\Exceptions;

use PavelEkt\BaseComponents\Abstracts\BaseMessageException;

class MethodNotFoundException extends BaseMessageException
{
    const EXCEPTION_CODE = 1002;
    const EXCEPTION_MESSAGE = 'Method \'{{method}}\' not found in class \'{{class}}\'.';
}
