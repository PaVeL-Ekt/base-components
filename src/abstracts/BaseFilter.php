<?php

namespace PavelEkt\BaseComponents\Abstracts;

use PavelEkt\BaseComponents\Interfaces\FilterInterface;

class BaseFilter extends BaseObject implements FilterInterface
{
    public function filter($value)
    {
        return $value;
    }
}
