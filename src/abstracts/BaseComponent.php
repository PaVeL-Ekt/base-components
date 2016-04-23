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
abstract class BaseComponent
{
    /**
     * @var mixed[] $_attributes Component attributes.
     */
    protected $_attributes = [];

    /**
     * @var mixed[] $_filters Component attributes filters.
     */
    protected $_filters = [];

    /**
     * @var bool $_isFilterAttributes Ключ, включающий возможность фильтрования атрибутов.
     */
    protected $_isFilterAttributes = true;

    /**
     * Standard component constructor
     * @param mixed[] $attributes initial attributes
     * @throws WrongFilterException
     */
    public function __construct($attributes = [])
    {
        if (!is_array($this->_attributes)) {
            $this->_attributes = [];
        }

        /**
         * Подготовим дополнительные фильтры.
         */
        $attributeFilters = [];
        if ($this->_isFilterAttributes) {
            foreach ($this->attributeFilters() as $attribute => $attributeFilter) {
                if ($filter = $this->getFilter($attribute, $attributeFilter)) {
                    $attributeFilters[] = $filter;
                } else {
                    throw new WrongFilterException($attribute, $attributeFilter);
                }
            }
        }

        /**
         * Подготовим передаваемые параметры
         */
        if (is_array($attributes)) {
            foreach ($attributes as $attribute => $value) {
                $newName = $this->getAttributeKey($attribute);
                if ($newName != $attribute) {
                    $attributes[$newName] = $value;
                    unset($attributes[$attribute]);
                }
            }
        } else {
            $attributes = [];
        }

        /**
         * Подготовим все атрибуты, подклбчим дополнительные фильтры.
         */
        foreach (
            [$this->_attributes, call_user_func([$this, 'extendedAttributes'])
        ] as $schemaId => $attributesData) {
            if (!is_array($attributesData)) {
                continue;
            }
            foreach ($attributesData as $attribute => $value) {
                // Значения по умолчанию
                $attrKey = $this->getAttributeKey($attribute);
                $attrValue = null;
                $attrFilters = [];

                if (ctype_digit((string) $attribute)) {
                    // Используется цифровой ключ
                    if ($filter = $this->getFilter($attribute, $value)) {
                        // Фильтр есть
                        if ($this->_isFilterAttributes) {
                            $attrFilters[] = $filter;
                        }
                    } else {
                        // Фильтра нет, если значение является скалярным, считаем его атрибутом
                        if (is_scalar($value)) {
                            $attrKey = $this->getAttributeKey($value);
                            unset($this->_attributes[$attribute]);
                            $attribute = $value;
                        } else {
                            throw new WrongFilterException($attribute, $value);
                        }
                    }
                } else {
                    // Ключ массива является именем атрибута
                    if ($attribute != $attrKey && $schemaId == 0) {
                        unset($this->_attributes[$attribute]);
                    }
                    if ($this->_isFilterAttributes && $filter = $this->getFilter($attribute, $value)) {
                        $attrFilters[] = $filter;
                    } else {
                        $attrValue = $value;
                    }
                }

                if ($this->_isFilterAttributes) {
                    if (array_key_exists($attrKey, $attributeFilters)) {
                        foreach ($attributeFilters[$attrKey] as $extFilter) {
                            if ($filter = $this->getFilter($attribute, $extFilter)) {
                                $attrFilters[] = $filter;
                            }
                        }
                    }
                }

                $this->_attributes[$attrKey] = [
                    'name' => $attribute,
                    'value' => $attrValue,
                    'filters' => $attrFilters,
                ];

                $this->$attrKey = (array_key_exists($attrKey, $attributes) ? $attributes[$attrKey] : $attrValue);
            }
        }
    }

    /**
     * Список дополнительных фильтров.
     * в формате
     * ```['attribute' => 'attributeName', 'filter' => 'filterClassName', 'params' => ['param1' => 'value', 'param2' => 'value']]```
     * или
     * ```['attribute' => new \PavelEkt\BaseComponents\Filters\DefaultFilter(['default' => 'default value']);
     * @return array
     */
    public function attributeFilters()
    {
        return [];
    }

    /**
     * Метод получение фильтра, из его описания.
     * @param string $attribute Название атрибута.
     * @param mixed $filter Описание фильтра.
     * @param bool $asObject Получить объектом.
     * @return array|bool|Object
     * @throws FilterNotFoundException
     * @throws WrongFilterException
     */
    protected function getFilter($attribute, $filter, $asObject = false)
    {
        if ($this->_isFilterAttributes) {
            if (is_array($filter)) {
                if (array_key_exists('filter', $filter)) {
                    if (!class_exists($filter['filter'])) {
                        throw new FilterNotFoundException($filter);
                    }
                    if (!array_key_exists('params', $filter)) {
                        $filter['params'] = [];
                    }
                    if ($asObject) {
                        return new $filter['filter'](array_key_exists('params', $filter) ? $filter['params'] : []);
                    } else {
                        return $filter;
                    }
                } elseif (array_key_exists('params', $filter) && array_key_exists('default', $filter['params'])) {
                    if ($asObject) {
                        return new DefaultFilter(['default' => $filter['params']['default']]);
                    } else {
                        return ['filter' => DefaultFilter::className(), 'params' => ['default' => $filter['params']['default']]];
                    }
                } else {
                    throw new WrongFilterException($attribute, $filter);
                }
            } elseif ($filter instanceof FilterInterface) {
                return $filter;
            } else {
                return new DefaultFilter(['default' => $filter]);
            }
        }
        return false;
    }

    /**
     * Фильтрация значения атрибута.
     * @param string $attribute Имя атрибута.
     * @param mixed $value Фильтруемое значение.
     * @return mixed
     * @throws FilterNotFoundException
     * @throws WrongFilterException
     */
    protected function filterAttributeValue($attribute, $value)
    {
        if ($this->_isFilterAttributes) {
            $attrKey = $this->getAttributeKey($attribute);
            if (array_key_exists($attrKey, $this->_attributes) && !empty($this->_attributes[$attrKey]['filters'])) {
                foreach ($this->_attributes[$attrKey]['filters'] as $filter) {
                    if (!$filter instanceof FilterInterface) {
                        $filter = $this->getFilter($this->_attributes[$attrKey]['name'], $filter, true);
                    }
                    if (!$filter) {
                        throw new WrongFilterException($this->_attributes[$attrKey]['name'], $filter);
                    }
                    $value = $filter->filter($value);
                }
            }
        }
        return $value;
    }

    /**
     * Return extended component attributes. For override.
     * Need return array. Example:
     * ```
     * [
     *      'AttrName' => 'filter',
     *      'AttrName',
     *      0 => 'AttrName'
     * ]
     * @return mixed[]
     */
    public function extendedAttributes()
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
        $attributeKey = $this->getAttributeKey($name);
        return method_exists($this, 'get' . $attributeKey) || array_key_exists($attributeKey, $this->_attributes);
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
        $attributeKey = $this->getAttributeKey($name);
        if (method_exists($this, 'get' . $attributeKey)) {
            return call_user_func([$this, 'get' . $attributeKey]);
        } elseif (array_key_exists($attributeKey, $this->_attributes)) {
            return ($this->_attributes[$attributeKey]['value']);
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
        $attributeKey = $this->getAttributeKey($name);
        if (method_exists($this, 'set' . $attributeKey)) {
            return call_user_func([$this, 'set' . $attributeKey], $value);
        } elseif (array_key_exists($attributeKey, $this->_attributes)) {
            $oldValue = $this->_attributes[$attributeKey]['value'];
            $this->_attributes[$attributeKey]['value'] = $this->filterAttributeValue($name, $value);
            return $oldValue;
        }
        throw new AttributeNotFoundException($name, $this);
    }

    /**
     * Стандартный метод проверки атрибута объекта, на существование.
     * @param string $name Имя атрибута.
     * @return bool
     */
    public function __isset($name) {
        $attributeKey = $this->getAttributeKey($name);
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

    protected function getAttributeKey($attributeName)
    {
        return strtolower(preg_replace('/[^a-z0-9]*/i', '', $attributeName));
    }

    /**
     * Возвращает имя класса для объекта или статического класа.
     * @return string
     */
    static public function className()
    {
        return get_called_class();
    }
}
