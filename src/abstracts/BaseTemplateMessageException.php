<?php

namespace PavelEkt\BaseComponents\Abstracts;

abstract class BaseTemplateMessageException extends BaseException
{
    const MESSAGE_TEMPLATE = 'Unknown application error in class {{class}}.';

    public function __construct($template = null, $params = [], $code = 9999, $previous = null)
    {
        parent::__construct(
            $this->generateExceptionMessage((!empty($template) ? $template : self::MESSAGE_TEMPLATE), $params),
            $code,
            $previous
        );
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
