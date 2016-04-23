<?php

namespace PavelEkt\BaseComponents\Interfaces;

interface ValidatorInterface
{
    /**
     * Валидация данных.
     * Вернет true в случае, если данные успешно валидированны, false иначе.
     * @param mixed $value Валидируемое значение.
     * @return boolean
     */
    public static function isValid($value);
}