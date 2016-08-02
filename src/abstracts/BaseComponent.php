<?php

namespace PavelEkt\BaseComponents\Abstracts;

use PavelEkt\BaseComponents\Exceptions\AttributeNotFoundException;
use PavelEkt\BaseComponents\Exceptions\FilterNotFoundException;
use PavelEkt\BaseComponents\Exceptions\MethodNotFoundException;
use PavelEkt\BaseComponents\Exceptions\WrongFilterException;
use PavelEkt\BaseComponents\Filters\DefaultFilter;
use PavelEkt\BaseComponents\Helpers\StringHelper;
use PavelEkt\BaseComponents\Interfaces\FilterInterface;

/**
 * Base components, for other packages.
 */
abstract class BaseComponent extends BaseObject
{
    /**
     * @var mixed[] $_attributes Component attributes.
     */
    private $_attributes = [];

    /**
     * @var bool $_isFilterAttributes Ключ, включающий возможность фильтрования атрибутов.
     */
    protected $_isFilterAttributes = true;

    /**
     * Protected methods section
     */

    /**
     * Служебный метод установки значения атрибута.
     * @param string        $name   Имя атрибута.
     * @param mixed         $value  Устанавливаемое значение атрибута.
     * @param null|string   $key    Ключ атрибута.
     * @return mixed
     * @throws AttributeNotFoundException
     */
    protected function _setAttribute($name, $value, $key = null)
    {
        if (is_null($key)) {
            $key = $this->_getAttributeKey($name);
        }
        if (!$this->_hasAttribute($name, $key)) {
            throw new AttributeNotFoundException($name, $this);
        }
        $prev = $this->_attributes[$key]['value'];
        $this->_attributes[$key]['value'] = $value;
        return $prev;
    }

    /**
     * Служебный метод, фильтрует и устанавливет значение атрибуту.
     * @param string        $name   Имя атрибута.
     * @param mixed         $value  Устанавливаемое значение.
     * @param null|string   $key    Ключ атрибута.
     * @return mixed
     * @throws AttributeNotFoundException
     */
    protected function _filterSetAttribute($name, $value, $key = null)
    {
        if (is_null($key)) {
            $key = $this->_getAttributeKey($name);
        }
        if (!$this->_hasAttribute($name, $key)) {
            throw new AttributeNotFoundException($name, $this);
        }
        if ($this->_isFilterAttributes) {
            $filters = $this->_getAttributeFilters($name, $key);
            foreach ($filters as $filter) {
                $value = $filter->filter($value);
            }
        }
        return $this->_setAttribute($name, $value, $key);
    }

    /**
     * Служебный метод получения значения атрибута.
     * @param $name
     * @param null $key
     * @return mixed
     * @throws AttributeNotFoundException
     */
    protected function _getAttribute($name, $key = null)
    {
        if (is_null($key)) {
            $key = $this->_getAttributeKey($name);
        }
        if (!array_key_exists($key, $this->_attributes)) {
            throw new AttributeNotFoundException($name, $this);
        }
        return $this->_attributes[$key]['value'];
    }

    /**
     * Служебный метод проверки, существует ли атрибут.
     * @param string        $name   Имя атрибута.
     * @param null|string   $key    Ключ атрибута.
     * @return bool
     */
    protected function _hasAttribute($name, $key = null)
    {
        if (is_null($key)) {
            $key = $this->_getAttributeKey($name);
        }
        return (method_exists($this, 'get' . $key) || array_key_exists($key, $this->_attributes));
    }

    /**
     * Служебный метод очистки атрибута.
     * @param string        $name   Имя атрибута.
     * @param null|string   $key    Ключ атрибута.
     */
    protected function _clearAttribute($name, $key = null)
    {
        if (is_null($key)) {
            $key = $this->_getAttributeKey($name);
        }
        $this->_filterSetAttribute($name, null, $key);
    }

    /**
     * Метод получение фильтра, из его описания.
     * @param string $attribute Название атрибута.
     * @param mixed $filter Описание фильтра.
     * @return array|bool|Object
     * @throws FilterNotFoundException
     * @throws WrongFilterException
     */
    protected function _parseRules($attribute, $filter)
    {
        $result = false;
        if ($this->_isFilterAttributes) {
            if (is_array($filter)) {
                if (array_key_exists('filter', $filter)) {
                    if (!class_exists($filter['filter'])) {
                        throw new FilterNotFoundException($filter);
                    }
                    if (!array_key_exists('params', $filter)) {
                        $filter['params'] = [];
                    }
                    $result = new $filter['filter'](array_key_exists('params', $filter) ? $filter['params'] : []);
                } elseif (array_key_exists('params', $filter) && array_key_exists('default', $filter['params'])) {
                    $result = new DefaultFilter(['default' => $filter['params']['default']]);
                } else {
                    foreach ($filter as $subFilter) {
                        $tmp = $this->_parseRules($attribute, $subFilter);
                        if ($tmp) {
                            if (!is_array($result)) {
                                $result = [];
                            }
                            $result[] = $tmp;
                        }
                    }
                    if ($filter === false) {
                        throw new WrongFilterException($attribute, $filter);
                    }
                }
            } elseif ($filter instanceof FilterInterface) {
                $result = $filter;
            } else {
                $result = new DefaultFilter(['default' => $filter]);
            }
        }
        return $result;
    }

    /**
     * Служебный метод, отдает все фильтры атрибута.
     * @param $name
     * @param null $key
     * @return mixed
     * @throws AttributeNotFoundException
     */
    protected function _getAttributeFilters($name, $key = null)
    {
        if (is_null($key)) {
            $key = $this->_getAttributeKey($name);
        }
        if (!$this->_hasAttribute($name, $key)) {
            throw new AttributeNotFoundException($name, $this);
        }
        return $this->_attributes[$key]['filters'];
    }

    /**
     * Служеный метод, добавляет фильтры элементу.
     * @param string        $name       Имя атрибута.
     * @param array         $filters    Добавляемые фильтры.
     * @param null|string   $key        Ключ атрибута.
     * @throws AttributeNotFoundException
     * @throws WrongFilterException
     * @throws FilterNotFoundException
     */
    protected function _setAttributeFilters($name, $filters = [], $key = null)
    {
        if (is_null($key)) {
            $key = $this->_getAttributeKey($name);
        }
        if (!$this->_hasAttribute($name, $key)) {
            throw new AttributeNotFoundException($name, $this);
        }
        if (!is_array($filters)) {
            $filters = [$filters];
        }
        foreach($filters as $filter) {
            if (is_array($filter)) {
                if (array_key_exists('filter', $filter)) {
                    if (!class_exists($filter['filter'])) {
                        throw new FilterNotFoundException($filter);
                    }
                    if (!array_key_exists('params', $filter)) {
                        $filter['params'] = [];
                    }
                    $this->_attributes[$key]['filters'][] = new $filter['filter'](
                        array_key_exists('params', $filter) ? $filter['params'] : []
                    );
                } elseif (array_key_exists('params', $filter) && array_key_exists('default', $filter['params'])) {
                    $this->_attributes[$key]['filters'][] = new DefaultFilter(
                        ['default' => $filter['params']['default']]
                    );
                } else {
                    throw new WrongFilterException($name, $filter);
                }
            } elseif ($filter instanceof FilterInterface) {
                $this->_attributes[$key]['filters'][] = $filter;
            } else {
                $this->_attributes[$key]['filters'][] = new DefaultFilter(['default' => $filter]);
            }
        }
    }

    /**
     * Служебный метод, проверяет, фильтруются ли значения атрибутов.
     * @param string        $name Имя атрибута.
     * @param null|string   $key  Ключ атрибута.
     * @throws AttributeNotFoundException
     * @return boolean
     */
    protected function _hasAttributeFilters($name, $key = null)
    {
        if (is_null($key)) {
            $key = $this->_getAttributeKey($name);
        }
        if (!$this->_hasAttribute($name, $key)) {
            throw new AttributeNotFoundException($name, $this);
        }
        return method_exists($this, 'set' . $key) || count($this->_attributes['filters']) > 0;
    }

    /**
     * Служебный метод получения ключа атрибута.
     * @param string $attributeName Название атрибута.
     * @return string
     */
    protected function _getAttributeKey($attributeName)
    {
        return strtolower(preg_replace('/[^a-z0-9]*/i', '', $attributeName));
    }

    /**
     * Public method section
     */

    /**
     * Standard component constructor
     * @param mixed[] $attributes initial attributes
     * @throws WrongFilterException
     */
    public function __construct($attributes = [])
    {
        if (is_array($attributes) && !empty($attributes)) {
            foreach ($attributes as $attribute => $value) {
                $key = $this->_getAttributeKey($attribute);
                if ($key != $attribute) {
                    $attributes[$key] = $value;
                    unset($attributes[$attribute]);
                }
            }
        }

        foreach($this->attributes() as $attribute => $value) {
            // Значения по умолчанию
            $attrKey = $this->_getAttributeKey($attribute);
            $attrValue = null;
            $attrFilters = [];

            if (ctype_digit((string) $attribute)) {
                // Используется цифровой ключ
                if (!$attrFilters = $this->_parseRules($attribute, $value)) {
                    // Фильтра нет, если значение является скалярным, считаем его атрибутом
                    if (is_scalar($value)) {
                        $attrKey = $this->_getAttributeKey($value);
                        $attribute = $value;
                    } else {
                        throw new WrongFilterException($attribute, $value);
                    }
                }
            } else {
                // Ключ массива является именем атрибута
                if ($this->_isFilterAttributes && $attrFilters = $this->_parseRules($attribute, $value)) {
                } else {
                    $attrValue = $value;
                }
            }

            if (!is_array($attrFilters)) {
                $attrFilters = [$attrFilters];
            }

            $this->_attributes[$attrKey] = [
                'name' => $attribute,
                'value' => null,
                'filters' => ($this->_isFilterAttributes ? $attrFilters : []),
            ];

            if (array_key_exists($attrKey, $attributes)) {
                $this->_filterSetAttribute($attribute, $attributes[$attrKey], $attrKey);
                unset($attributes[$attrKey]);
            } else {
                $this->_filterSetAttribute($attribute, $attrValue, $attrKey);
            }
        }

        if (is_array($attributes)) {
            foreach ($attributes as $attribute => $value) {
                $this->$attribute = $value;
            }
        }
    }

    /**
     * Return extended component attributes. For override.
     * Need return array. Example:
     * ```
     * [
     *      'AttrName' => ['filter'=>'DefaultFilter', 'params'=>['default' => 100]],
     *      'AttrName' => new DefaultFilter(['default' => 100]);
     *      'AttrName',
     *      0 => 'AttrName'
     * ]
     * @return mixed[]
     */
    public function attributes()
    {
        return [];
    }

    /**
     * Check have component attribute by name.
     * @param string $name Attribute name.
     * @return bool If component have attribute, return true. False otherwise.
     */
    public function hasAttribute($name)
    {
        return $this->_hasAttribute($name);
    }

    /**
     * Get component attribute.
     * @param string $name Attribute name.
     * @return mixed Attribute value
     * @throws AttributeNotFoundException
     */
    public function getAttribute($name)
    {
        return $this->__get($name);
    }

    /**
     * Set component attribute.
     * @param string $name Attribute name.
     * @param mixed $value Attribute value.
     * @return mixed Old attribute value.
     * @throws AttributeNotFoundException.
     */
    public function setAttribute($name, $value)
    {
        return $this->__set($name, $value);
    }

    /**
     * Standard component caller.
     * @param string $name Component method name.
     * @param array $env Method transfer parameters.
     * @return mixed Method returned result.
     * @throws MethodNotFoundException
     */
    public function __call($name, $env = [])
    {

        if (stripos($name, 'has') === 0) {
            return $this->hasAttribute(substr($name, 3));
        } elseif (method_exists($this, 'call' . $name)) {
            return call_user_func_array([$this, 'call' . $name], $env);
        }
        throw new MethodNotFoundException($name, $this);
    }

    /**
     * Standard component attribute getter.
     * @param string $name Attribute name.
     * @return mixed Attribute value.
     * @throws AttributeNotFoundException
     */
    public function __get($name)
    {
        $attributeKey = $this->_getAttributeKey($name);
        if (method_exists($this, 'get' . $attributeKey)) {
            return call_user_func([$this, 'get' . $attributeKey]);
        } elseif (array_key_exists($attributeKey, $this->_attributes)) {
            return $this->_getAttribute($attributeKey);
        }
        throw new AttributeNotFoundException($name, $this);
    }

    /**
     * Standard component attribute setter.
     * @param string $name Attribute name to set.
     * @param mixed $value Attribute value to set
     * @return mixed Old attribute value.
     * @throws AttributeNotFoundException
     */
    public function __set($name, $value)
    {
        $key = $this->_getAttributeKey($name);
        if (method_exists($this, 'set' . $key)) {
            return call_user_func([$this, 'set' . $key], $value);
        }
        if (!$this->_hasAttribute($name, $key)) {
            throw new AttributeNotFoundException($name, $this);
        }
        return $this->_filterSetAttribute($name, $value, $key);
    }

    /**
     * Стандартный метод проверки атрибута объекта, на существование.
     * @param string $name Имя атрибута.
     * @return bool
     */
    public function __isset($name) {
        $attributeKey = $this->_getAttributeKey($name);
        if (
            method_exists($this, 'get' . $attributeKey) ||
            method_exists($this, 'set' . $attributeKey) ||
            array_key_exists($attributeKey, $this->_attributes)
        ) {
            return true;
        }
        return false;
    }

    /**
     * Преобразование объекта в строку.
     * @return string
     */
    public function __toString()
    {
        $result = '';
        $attributes = [];
        foreach ($this->_attributes as $data) {
            $attributes[$data['name']] = $data['value'];
        }
        $reflection = new \ReflectionClass($this);
        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            $propName = $reflectionProperty->name;
            $attributes[$propName] = $this->$propName;
        }
        ksort($attributes);
        foreach ($attributes as $attributeName => $attributeValue) {
            $result .= ', ' . StringHelper::printVar($attributeName) . '=>' . StringHelper::printVar($attributeValue);
        }
        return 'Object(' . $this->className() . '){' . trim($result, ', ') . '}';
    }

    /**
     * Static public section.
     */

    /**
     * Возвращает имя класса для объекта или статического класа.
     * @return string
     */
    static public function className()
    {
        return get_called_class();
    }
}
