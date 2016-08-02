<?php

namespace PavelEkt\BaseComponents\Abstracts;

abstract class BaseException extends \Exception
{
    /**
     * Вычисляем класс, который вызвал исключение.
     * @param null|mixed[] $params Параметры
     * @return string
     */
    protected function getExceptionClass($params = null)
    {
        if (is_array($params)) {
            if (!empty($params['class'])) {
                $params = $params['class'];
            } else {
                $params = null;
            }
        }
        if (is_string($params)) {
            return $params;
        }
        if (is_object($params)) {
            return get_class($params);
        }
        if (function_exists('debug_backtrace')) {
            $stackTrace = debug_backtrace(!DEBUG_BACKTRACE_PROVIDE_OBJECT && DEBUG_BACKTRACE_IGNORE_ARGS);
            return $stackTrace[count($stackTrace) - 1]['class'];
        }
        return 'Unknown class';
    }

    static public function getClass()
    {
        return get_called_class();
    }
}
