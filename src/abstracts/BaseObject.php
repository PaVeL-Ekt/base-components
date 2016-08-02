<?php

namespace PavelEkt\BaseComponents\Abstracts;

use PavelEkt\BaseComponents\Exceptions\AttributeNotFoundException;
use PavelEkt\BaseComponents\Exceptions\WrongAttributeNameException;

abstract class BaseObject
{
    /**
     * Regexp pattern for validation attribute name.
     */
    const ATTRIBUTE_REGEXP = '/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/';

    /**
     * @var array $_attributes List of object attributes.
     */
    private $_attributes = [];

    /**
     * @var bool $_isInitAttribute Flag initialize attributes
     */
    private $_isInitAttribute = false;

    /**
     * Protected section
     */

    /**
     * A utility method that checks attribute name.
     * @param string $name
     * @return boolean
     */
    protected function _isValidAttributeName($name)
    {
        return (bool) preg_match(self::ATTRIBUTE_REGEXP, $name);
    }

    /**
     * A utility method that generates a key attribute (normalize name).
     * @param string $attributeName Attribute name for generating key.
     * @return string Attribute key.
     */
    protected function _getAttributeKey($attributeName)
    {
        return strtolower($attributeName);
    }

    /**
     * A utility method that checks for the attribute and returns its value.
     * @param string $name Attribute name.
     * @param null|string $key Attribute key (normalized name).
     * @return mixed|null Attribute value.
     * @throws AttributeNotFoundException Throws when attribute not found.
     */
    protected function _getAttribute($name, $key = null)
    {
        if (is_null($key)) {
            $key = $this->_getAttributeKey($name);
        }
        if (method_exists($this, 'get' . $key)) {
            return call_user_func([$this, 'get' . $key]);
        } elseif ($this->_hasAttribute($name, $key)) {
            return $this->_attributes[$key];
        }
        throw new AttributeNotFoundException($name, $this);
    }

    /**
     * A utility method that checks for the attribute and set its value.
     * @param string $name Attribute name.
     * @param mixed $value Attribute value.
     * @param string|null $key Attribute key (normalized name).
     * @throws AttributeNotFoundException Throws when attribute not found.
     */
    protected function _setAttribute($name, $value, $key = null)
    {
        if (is_null($key)) {
            $key = $this->_getAttributeKey($name);
        }
        if (method_exists($this, 'set' . $key)) {
            call_user_func([$this, 'set' . $key], $value);
        } elseif ($this->_hasAttribute($name, $key)) {
            $this->_attributes[$key] = $value;
        } else {
            throw new AttributeNotFoundException($name, $this);
        }
    }

    /**
     * A utility method that set defaults value for the attributes.
     * @throws WrongAttributeNameException
     */
    private function _initAttributes()
    {
        if (method_exists($this, 'initAttributes')) {
            $attributes = $this->initAttributes($this->attributes());
        }
        if (empty($attributes)) {
            $attributes = $this->attributes();
        }
        foreach ($attributes as $objKey => $objValue) {
            if ($this->_isValidAttributeName($objKey)) {
                $this->_attributes[$this->_getAttributeKey($objKey)] = $objValue;
            } elseif (intval($objKey) == $objKey && $this->_isValidAttributeName($objValue)) {
                $this->_attributes[$this->_getAttributeKey($objValue)] = null;
            } else {
                throw new WrongAttributeNameException($objKey);
            }
        }
        $this->_isInitAttribute = true;
    }

    /**
     * A utility method that checks for the attribute.
     * @param string $name Attribute name.
     * @param string|null $key Attribute key.
     * @return bool
     */
    protected function _hasAttribute($name, $key = null)
    {
        if (!$this->_isInitAttribute) {
            $this->_initAttributes();
        }
        if (is_null($key)) {
            $key = $this->_getAttributeKey($name);
        }
        if (array_key_exists($key, $this->_attributes)) {
            return true;
        }
        return false;
    }

    protected function _isEmpty($name, $key = null)
    {
        if (is_null($key)) {
            $key = $this->_getAttributeKey($key);
        }
        if ($this->hasAttribute($name, $key)) {
            return empty($this->_attributes[$key]);
        }
        throw new AttributeNotFoundException($name, $this);
    }

    /**
     * Public section
     */

    /**
     * Base constructor for object
     * @param mixed[] $attributes
     */
    public function __construct($attributes = [])
    {
        $this->_initAttributes();
        if (method_exists($this, 'init')) {
            $attributes = $this->init($attributes);
        }
        if (is_array($attributes)) {
            $this->setAttributes($attributes);
        }
    }

    /**
     * Base getter for object.
     * @param string $name Attribute name.
     * @return mixed Attribute value.
     */
    public function __get($name)
    {
        if ($this->_hasAttribute($name)) {

        }
        $attributeKey = $this->_getAttributeKey($name);
        if (method_exists($this, 'get' . $attributeKey)) {
            return call_user_func([$this, 'get' . $attributeKey]);
        }
        return $this->_getAttribute($attributeKey);
    }

    /**
     * Base setter for object.
     * @param string $name Attribute name.
     * @param mixed $value Attribute value.
     * @return mixed Previous value.
     */
    public function __set($name, $value)
    {
        $attributeKey = $this->_getAttributeKey($name);
        if (method_exists($this, 'set' . $attributeKey)) {
            call_user_func([$this, 'set' . $attributeKey]);
        } else {
            $this->_setAttribute($name, $value, $attributeKey);
        }
    }

    /**
     * The method returns list of attribute names.
     * @return string[]
     */
    public function attributes()
    {
        return [];
    }

    /**
     * The method returns attribute value.
     * @param string $name Attribute name.
     * @return mixed|null
     * @throws AttributeNotFoundException
     */
    public function getAttribute($name)
    {
        return $this->_getAttribute($name);
    }

    /**
     * The method returns the list of attributes and his values.
     * @return mixed[]
     */
    public function getAttributes()
    {
        return $this->_attributes;
    }

    /**
     * The method that checks for the attribute.
     * @param $name
     * @return bool
     */
    public function hasAttribute($name)
    {
        return $this->_hasAttribute($name);
    }

    /**
     * The method that sets attribute value.
     * @param string $name Attribute name.
     * @param mixed $value Attribute value.
     * @throws AttributeNotFoundException
     */
    public function setAttribute($name, $value)
    {
        $this->_setAttribute($name, $value);
    }

    /**
     * The method that sets attribute values.
     * @param mixed[] $attributes List of attributes.
     * @param bool $quiet Quiet mode use flag.
     * @return bool
     * @throws \Exception
     */
    public function setAttributes($attributes, $quiet = false)
    {
        $result = true;
        if (!is_array($attributes)) {
            $attributes = [$attributes];
        }
        foreach ($attributes as $name => $value) {
            try {
                $this->_setAttribute($name, $value);
            } catch (\Exception $e) {
                if (!$quiet) {
                    throw $e;
                }
                $result = false;
            }
        }
        return $result;
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
