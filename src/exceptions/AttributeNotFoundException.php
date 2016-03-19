<?php

namespace PavelEkt\BaseComponents\Exceptions;

use PavelEkt\BaseComponents\Abstracts\BaseMessageException;

class AttributeNotFoundException extends BaseMessageException
{
    const EXCEPTION_CODE = 1001;
    const EXCEPTION_MESSAGE = 'Attribute \'{{attribute}}\' not found in class \'{{class}}\'.';
}
