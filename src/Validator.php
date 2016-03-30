<?php

namespace PavelEkt\BaseComponents;

class Validator
{
    public static function validateUrl($url)
    {
        return !(empty($url) || filter_var($url, FILTER_VALIDATE_URL) === false);
    }

    public static function validateEmail($email)
    {
        return !(empty($email) || filter_var($email, FILTER_VALIDATE_EMAIL) === false);
    }
}