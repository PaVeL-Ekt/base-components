<?php

namespace PavelEkt\BaseComponents\Abstracts;

use PavelEkt\BaseComponents\Exceptions\AttributeNotFoundException;
use PavelEkt\BaseComponents\Exceptions\MethodNotFoundException;

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
     * Standard component constructor
     * @param mixed[] $attributes initial attributes
     */
    public function __construct($attributes = [])
    {
        /** Set extended attributes */
        if (method_exists($this, 'getExtAttributes')) {
            $extAttributes = call_user_func([$this, 'getExtAttributes']);
            if (is_array($extAttributes) && !empty($extAttributes)) {
                foreach ($extAttributes as $key => $value) {
                    if (intval($key) == $key) {
                        $this->_attributes[strtolower($value)] = null;
                    } else {
                        $this->_attributes[strtolower($key)] = $value;
                    }
                }
            }
        }
        /** Fill initialized attributes */
        if (is_array($attributes) && !empty($attributes)) {
            foreach ($attributes as $name => $value) {
                $lowName = strtolower($name);
                if (array_key_exists($lowName, $this->_attributes)) {
                    $this->_attributes[$lowName] = $value;
                }
            }
        }
    }

    /**
     * Return extended component attributes. For override.
     * Need return array. Example:
     * ```
     * [
     *      'AttrName' => 'DefaultValue',
     *      'AttrName',
     *      0 => 'AttrName'
     * ]
     * @return mixed[]
     */
    public function getExtAttributes()
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
        $name = strtolower($name);
        return method_exists($this, 'get' . $name) || array_key_exists($name, $this->_attributes);
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
        $lowName = strtolower($name);
        if (method_exists($this, 'get' . $lowName)) {
            return call_user_func([$this, 'get' . $lowName]);
        } elseif (array_key_exists($lowName, $this->_attributes)) {
            return ($this->_attributes[$lowName]);
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
        $lowName = strtolower($name);
        if (method_exists($this, 'set' . $lowName)) {
            return call_user_func([$this, 'set' . $lowName], $value);
        } elseif (array_key_exists($lowName, $this->_attributes)) {
            $oldValue = $this->_attributes[$lowName];
            $this->_attributes[$lowName] = $value;
            return $oldValue;
        }
        throw new AttributeNotFoundException($name, $this);
    }
}
