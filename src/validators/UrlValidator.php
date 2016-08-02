<?php

namespace PavelEkt\BaseComponents\Validators;

use PavelEkt\BaseComponents\Abstracts\BaseValidator;

/**
 * Class Url
 * Валидация Url
 * @package PavelEkt\BaseComponents\Validators
 */
class UrlValidator extends BaseValidator
{
    // Old validate regexp
    // "/^((?:(?P<scheme>\w+):)?(\/\/)?(?:(?P<login>\w+):(?P<pass>\w+)@)?(?P<host>(?:(?P<subdomain>[\w\.]+)\.)?(?P<domain>\w+\.(?P<extension>\w+)))(?::(?P<port>\d+))?)?(?P<path>[\w\/]*\/(?P<file>\w+(?:\.\w+)?)?)?(?:\?(?P<arg>[\w=&]+))?(?:#(?P<anchor>\w+))?$/"
    const VALIDATE_REGEXP = "/^((?:(?P<scheme>\w+):)?(\/\/)?(?:(?P<login>\w+):(?P<pass>\w+)@)?(?P<host>(?:(?P<subdomain>[\w\.]+)\.)?(?P<domain>\w+\.(?P<extension>\w+)))(?::(?P<port>\d+))?)?(?P<path>[\w\/\.]*\/?)?(?:\?(?P<arg>[\w=&]+))?(?:#(?P<anchor>\w+))?$/";

    public function isValid($url)
    {
        if (!empty($url)) {
            return (bool) preg_match(self::VALIDATE_REGEXP, $url, $matches) && $matches != [''];
        }
        return false;
    }
}
