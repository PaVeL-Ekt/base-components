<?php

namespace PavelEkt\BaseComponents\Abstracts;

abstract class BaseTemplateException extends \Exception
{
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

    /**
     * Генерирует текст исключения из шаблона.
     * @param string $template Шаблон текста исключения.
     * @param array $params Параметры замены.
     * @return string
     */
    protected function generateExceptionMessage($template = '', $params = [])
    {
        if (!empty($template)) {
            $patterns = [];
            $replacements = [];
            $classKeyExist = false;
            foreach ($params as $key => $value) {
                $patterns[] = '/{{' . $key . '}}/uim';
                if (strtolower($key) == 'class') {
                    $classKeyExist = true;
                    $replacements[] = $this->getExceptionedClass($params);
                } else {
                    $replacements[] = $value;
                }
            }
            if (!$classKeyExist) {
                $patterns[] = '/{{class}}/uim';
                $replacements[] = $this->getExceptionedClass($params);
            }
            return preg_replace($patterns, $replacements, $template);
        }
        return '';
    }
}
