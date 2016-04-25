<?php

namespace PavelEkt\BaseComponents\Helpers;

use PavelEkt\BaseComponents\Exceptions\BadEncodingException;

class StringHelper
{
    const STR_CROP_LEFT = 0;
    const STR_CROP_BOTH = 1;
    const STR_CROP_RIGHT = 2;
    /**
     * @var string Используемая по умолчанию кодировка.
     */
    static private $_defaultEncoding = 'UTF-8';

    /**
     * Служебный метод, проверяет, доступна ли кодировка в системе. Поиск ведется по нормализованному названию кодировки.
     * @param string $encoding Название кодировки
     * @return null|string
     */
    static protected function _getEncoding($encoding)
    {
        if (!empty($encoding)) {
            $normalizeEncoding = strtoupper(preg_replace('/[^a-z0-9]*/i', '', $encoding));
            $listEncodings = [];
            array_map(function($element) use (&$listEncodings, $normalizeEncoding) {
                $normalizeElement = strtoupper(preg_replace('/[^a-z0-9]*/i', '', $element));
                if ($normalizeElement == $normalizeEncoding) {
                    $listEncodings[] = $element;
                }
            }, mb_list_encodings());
            if (!empty($listEncodings)) {
                if (count($listEncodings) > 1 && in_array($encoding, $listEncodings)) {
                    return $encoding;
                } else {
                    return $listEncodings[0];
                }
            }
        }
        return null;
    }

    /**
     * Проверить, можно ли использовать указанную кодировку.
     * @param string $encoding Проверяемый кодировка.
     * @return bool
     */
    static public function checkEncoding($encoding)
    {
        $encoding = static::_getEncoding($encoding);
        return !empty($encoding);
    }

    /**
     * Установить используемую по умолчанию кодировку.
     * @param string $encoding Устанавливаемая кодировка.
     * @throws BadEncodingException
     */
    static public function setDefaultEncoding($encoding = 'UTF-8')
    {
        $sysEncoding = static::_getEncoding($encoding);
        if (empty($sysEncoding)) {
            throw new BadEncodingException(['encoding' => $encoding]);
        }
        static::$_defaultEncoding = $sysEncoding;
    }

    /**
     * Получить кодировку, используемую по умолчанию.
     * @return string
     */
    static public function getDefaultEncoding()
    {
        return static::$_defaultEncoding;
    }

    /**
     * Проверяет указанную кодировку, если ее нет в списке доступных, генерирует исключение.
     * Если передается пустое значение, тогда возвращает кодировку по умолчанию.
     * @param string $encoding Проверяемая кодировка.
     * @return string
     * @throws BadEncodingException
     */
    static protected function getEncoding($encoding)
    {
        if (empty($encoding)) {
            return static::$_defaultEncoding;
        } else {
            if (!static::checkEncoding($encoding)) {
                throw new BadEncodingException(['encoding' => $encoding]);
            }
        }
        return $encoding;
    }

    /**
     * Дополнить строку, другой строкой. Поддерживаются многобайтовые кодировки.
     * Если строка превышает установленную длинну, то она не модифицируется.
     * Дополняемая строка будет обрезана, если полностью не войдет в лимит.
     * ```
     * echo StringHelper::strPad('test', 10, '<=====>', STR_PAD_BOTH);
     * ```
     * выведет `==>test<==`
     * @param string $str Исходная строка.
     * @param int $maxLen Длинна до которой нужно дополнить строку.
     * @param string $padStr Строка, которой дополняем исходную строку.
     * @param int $padType Тип заполнения (STR_PAD_LEFT, STR_PAD_BOTH, STR_PAD_RIGHT)
     * @param null|string $encoding Используемая кодировка.
     * @return string
     */
    static public function strPad($str, $maxLen, $padStr, $padType = STR_PAD_RIGHT, $encoding = null)
    {
        $encoding = self::getEncoding($encoding);
        $padType = self::__getPadType($padType);

        $strLen = mb_strlen($str, $encoding);
        if ($strLen >= $maxLen) {
            return $str;
        }
        $padLen = $maxLen - $strLen;
        if ($padType == STR_PAD_LEFT) {
            return self::__createPadLStr($padStr, $padLen, $encoding) . $str;
        } elseif ($padType == STR_PAD_RIGHT) {
            return $str . self::__createPadRStr($padStr, $padLen, $encoding);
        }
        $leftLen = ceil($padLen / 2);
        return self::__createPadLStr($padStr, $leftLen, $encoding) . $str . self::__createPadRStr($padStr, $padLen - $leftLen, $encoding);
    }

    /**
     * Дополнить строку, другой строкой. Поддерживаются многобайтовые кодировки.
     * Если строка превышает установленную длинну, то она не модифицируется.
     * Дополняемая строка будет обрезана, если полностью не войдет в лимит.
     * Обрезание дополняемых строк происходит с другой стороны в отличии от StringHelper::strPad
     * ```
     * echo StringHelper::strRPad('test', 10, '<=====>', STR_PAD_BOTH);
     * ```
     * выведет `<==test==>`
     * @param string $str Исходная строка.
     * @param int $maxLen Длинна до которой нужно дополнить строку.
     * @param string $padStr Строка, которой дополняем исходную строку.
     * @param int $padType Тип заполнения (STR_PAD_LEFT, STR_PAD_BOTH, STR_PAD_RIGHT)
     * @param null|string $encoding Используемая кодировка.
     * @return string
     */
    static public function strRPad($str, $maxLen, $padStr, $padType = STR_PAD_RIGHT, $encoding = null)
    {
        $encoding = self::getEncoding($encoding);
        $padType = self::__getPadType($padType);

        $strLen = mb_strlen($str, $encoding);
        $padLen = $maxLen - $strLen;
        if ($padType == STR_PAD_LEFT) {
            return self::__createPadRStr($padStr, $padLen, $encoding) . $str;
        } elseif ($padType == STR_PAD_RIGHT) {
            return $str . self::__createPadLStr($padStr, $padLen, $encoding);
        }
        $leftLen = ceil($padLen / 2);
        return self::__createPadRStr($padStr, $leftLen, $encoding) . $str . self::__createPadLStr($padStr, $padLen - $leftLen, $encoding);
    }

    static private function __createPadRStr($padStr, $maxLen, $encoding)
    {
        $padStrLen = mb_strlen($padStr, $encoding);
        if ($padStrLen == $maxLen) {
            return $padStr;
        }
        $str = '';
        if ($padStrLen < $maxLen) {
            for ($i = 0, $ic = floor($maxLen / $padStrLen); $i < $ic; ++$i) {
                $str .= $padStr;
            }
            $fillStr = $padStrLen * $i;
            if ($fillStr == $maxLen) {
                return $str;
            }
        } else {
            $fillStr = 0;
        }
        return $str . mb_substr($padStr, 0, $maxLen - $fillStr, $encoding);
    }

    static private function __createPadLStr($padStr, $maxLen, $encoding)
    {
        $padStrLen = mb_strlen($padStr, $encoding);
        if ($padStrLen == $maxLen) {
            return $padStr;
        }
        $str = '';
        if ($padStrLen < $maxLen) {
            for ($i = 0, $ic = floor($maxLen / $padStrLen); $i < $ic; ++$i) {
                $str .= $padStr;
            }
            $fillStr = $padStrLen * $i;
            if ($fillStr == $maxLen) {
                return $str;
            }
        } else {
            $fillStr = 0;
        }
        return mb_substr($padStr, $padStrLen - ($maxLen - $fillStr), null, $encoding) . $str;
    }

    static private function __getPadType($strPadType = STR_PAD_RIGHT)
    {
        if (!in_array($strPadType, [STR_PAD_LEFT, STR_PAD_BOTH, STR_PAD_RIGHT])) {
            $strPadType = STR_PAD_RIGHT;
        }
        return $strPadType;
    }

    /**
     * Обрезать строку справа, до нужной длинны. Поддерживаются многобайтовые кодировки.
     * Если строка превышает установленную длинну, то последними 3-мя символами станут точки.
     * @param string $str Исходная строка.
     * @param int $maxLen Длинна до которой нужно обрезать строку.
     * @param int $cropType Тип обрезания строки.
     * Варианты: static::STR_CROP_LEFT - слева, static::STR_CROP_BOTH - с обеих сторон, static::STR_CROP_RIGHT - справа.
     * По умолчанию: static::STR_CROP_RIGHT.
     * @param null|string $encoding Используемая кодировка.
     * @return string
     */
    static public function strCrop($str, $maxLen, $cropType = self::STR_CROP_RIGHT, $encoding = null)
    {
        if (is_null($maxLen)) {
            return $str;
        }
        if (!is_int($maxLen)) {
            $maxLen = intval($maxLen);
        }
        if ($maxLen === 0) {
            return '';
        }
        $encoding = static::getEncoding($encoding);
        if (!in_array($cropType, [self::STR_CROP_LEFT, self::STR_CROP_BOTH, self::STR_CROP_RIGHT])) {
            $cropType = self::STR_CROP_RIGHT;
        }
        $strLen = mb_strlen($str, $encoding);
        if ($strLen <= $maxLen) {
            return $str;
        }
        if ($cropType == self::STR_CROP_LEFT) {
            return '...' . mb_substr($str, $strLen - ($maxLen - 3), null, $encoding);
        }
        if ($cropType == self::STR_CROP_RIGHT) {
            return mb_substr($str, 0, $maxLen - 3, $encoding) . '...';
        }
        $cropLen = $maxLen - 6;
        $beforeLen = ceil(($strLen - $cropLen) / 2);
        return '...' . mb_substr($str, $beforeLen, $cropLen, $encoding) . '...';
    }

    /**
     * Замена подстроки в строке. С поддержкой мультибайтовых кодировок.
     * @param string $str Исходная строка.
     * @param string $search Искомая строка.
     * @param string $replace Строка на которую заменяем.
     * @param null|string $encoding Используемая кодировка.
     * @param int &$count Количество проведенных замен.
     * @return string
     */
    static public function strReplace($str, $search, $replace, $encoding = 'UTF-8', &$count = null)
    {
        $searchLen = mb_strlen($search, $encoding);
        $replaceCount = 0;
        while ($searchPos = mb_strpos($str, $search, null, $encoding)) {
            $replaceCount++;
            $str =
                mb_substr($str, 0, $searchPos, $encoding) . $replace .
                mb_substr($str, $searchPos + $searchLen, null, $encoding);
        }
        if (isset($count)) {
            $count = $replaceCount;
        }
        return $str;
    }

    /**
     * Приведение типа к String
     * @param mixed $value Исходное значение
     * @return string
     */
    static public function toStr($value)
    {
        if (!is_string($value)) {
            if (is_scalar($value)) {
                if (is_bool($value)) {
                    $value = $value ? 'true' : 'false';
                } else {
                    $value = strval($value);
                }
            } else {
                $value = static::printVar($value);
            }
        }
        return $value;
    }

    /**
     * Преобразует переменную любого типа в строку
     * @param mixed $value
     * @return string
     */
    static public function printVar($value)
    {
        $result = '';
        if (is_string($value)) {
            $result = '\'' . $value . '\'';
        } elseif (is_null($value)) {
            $result = 'NULL';
        } elseif (is_bool($value)) {
            $result = ($value ? 'true' : 'false');
        } elseif (is_int($value) || is_float($value)) {
            $result = strval($value);
        } elseif (is_array($value) || is_object($value)) {
            if (is_object($value) && method_exists($value, '__toString')) {
                $result = strval($value);
            } else {
                foreach ($value as $key => $data) {
                    $result .= ',' . static::printVar($key) . '=>' . static::printVar($data);
                }
                $result = trim($result, ',');
                if (is_array($value)) {
                    $result = '[' . $result . ']';
                } else {
                    $result = 'Object(' . get_class($value) . '){' . $result . '}';
                }
            }
        } elseif (is_resource($value)) {
            $result = 'Resource(' . get_resource_type($value) . ')';
        } elseif (is_callable($value)) {
            $result = 'Callable';
        }
        return $result;
    }
}
