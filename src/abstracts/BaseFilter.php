<?php

namespace PavelEkt\BaseComponents\Abstracts;

use PavelEkt\BaseComponents\Interfaces\FilterInterface;

class BaseFilter extends BaseComponent implements FilterInterface
{
    protected $_isFilterAttributes = false;

    public function filter($value)
    {
        return $value;
    }
}
