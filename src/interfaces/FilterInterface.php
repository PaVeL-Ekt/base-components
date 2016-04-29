<?php

namespace PavelEkt\BaseComponents\Interfaces;

/**
 * Interface FilterInterface
 * Интерфейс для компонентов фильтрации.
 * @package PavelEkt\BaseComponents\Interfaces
 */
interface FilterInterface
{
    /**
     * Преобразует значение, согласно фильтру.
     * @param mixed $value Значение, которое нужно фильтровать.
     * @return mixed
     */
    public function filter($value);
}
