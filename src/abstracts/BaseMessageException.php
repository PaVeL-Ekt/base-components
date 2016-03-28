<?php

namespace PavelEkt\BaseComponents\Abstracts;

abstract class BaseMessageException extends \Exception
{
    /**
     * @const int EXCEPTION_CODE The Exception code.
     */
    const EXCEPTION_CODE = 9999;
    /**
     * @const string EXCEPTION_MESSAGE The Exception message.
     */
    const EXCEPTION_MESSAGE = 'Unknown application error in class {{class}}.';

    public function __construct($params = [], $previous = null)
    {
        /**
         * Заменим переменные в тексте сообщения.
         */
        $expressions = [];
        $replacements = [];
        if (is_array($params)) {
            foreach ($params as $key => $value) {
                if (strtolower($key) !== 'class') {
                    $expressions[] = '/\{\{' . $key . '\}\}/ium';
                    $replacements[] = $value;
                }
            }
        }
        /**
         * Вычислим клас, в котором произошла ошибка, и заменим переменную в шаблоне.
         */
        $expressions[] = '/\{\{class\}\}/ium';
        $replacements[] = $this->getExceptionedClass($params);
        /**
         * Удалим все неиспользованные переменные в шаблоне.
         */
        $expressions[] = '/\{\{[a-z0-9-_]*\}\}/ium';
        $replacements[] = '';

        $message = preg_replace($expressions, $replacements, static::EXCEPTION_MESSAGE);
        parent::__construct($message, static::EXCEPTION_CODE, $previous);
    }

    /**
     * Вычисляем класс, который вызвал исключение.
     * @param null|mixed[] $params Параметры
     * @return string
     */
    protected function getExceptionedClass($params = null)
    {
        $result = '';
        if (!empty($params['class'])) {
            if (is_object($params['class'])) {
                $result = get_class($params['class']);
            }
        }
        if (empty($params['class']) && function_exists('debug_backtrace')) {
            $stackTrace = debug_backtrace(!DEBUG_BACKTRACE_PROVIDE_OBJECT && DEBUG_BACKTRACE_IGNORE_ARGS);
            $result = $stackTrace[count($stackTrace) - 1]['class'];
        }
        return $result;
    }
}