<?php

namespace PavelEkt\BaseComponents\Exceptions;

use PavelEkt\BaseComponents\Abstracts\BaseMessageException;

class BadEncodingException extends BaseMessageException
{
    const EXCEPTION_CODE = 1003;
    const EXCEPTION_MESSAGE = 'Trying to use unsupported encoding \'{{encoding}}\'.';
}