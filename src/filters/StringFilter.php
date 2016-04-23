<?php

namespace PavelEkt\BaseComponents\Filters;

use PavelEkt\BaseComponents\Abstracts\BaseFilter;
use PavelEkt\BaseComponents\Helpers\StringHelper;

/**
 * Class StringFilter
 * Фильтр, для текстовых строк.
 * @package PavelEkt\BaseComponents\Filters
 * @property string $default    Строка по умолчанию.
 * @property int    $minLength  Минимильная длинна строки.
 * @property string $padString  Строка, которой будет дополнятся фильтруемая, до минимального значения.
 * @property int    $padType    Тип заполнения (STR_PAD_LEFT, STR_PAD_BOTH, STR_PAD_RIGHT)
 * @property int    $maxLength  Максимальная длинна строки.
 * @property int    $cropType   Тип обрезания строки (StringHelper::STR_CROP_LEFT, StringHelper::STR_CROP_BOTH, StringHelper::STR_CROP_RIGHT).
 */
class StringFilter extends BaseFilter
{
    protected $_attributes = [
        'default'   => '',
        'minLength' => 0,
        'padString' => ' ',
        'padType'   => STR_PAD_RIGHT,
        'maxLength' => null,
        'cropType'  => StringHelper::STR_CROP_RIGHT,
    ];

    public function filter($value)
    {
        if (is_null($value)) {
            $value = $this->default;
        }
        $value = StringHelper::toStr($value);
        return StringHelper::strCrop(
            StringHelper::strPad(
                $value,
                $this->minLength,
                $this->padString,
                $this->padType
            ),
            $this->maxLength,
            $this->cropType
        );
    }
}
