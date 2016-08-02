<?php

namespace PavelEkt\BaseComponents\Abstracts;

use PavelEkt\BaseComponents\Exceptions\AttributeValidationFailedException;
use PavelEkt\BaseComponents\Filters\DefaultFilter;
use PavelEkt\BaseComponents\Interfaces\FilterInterface;
use PavelEkt\BaseComponents\Interfaces\ValidatorInterface;

abstract class BaseAttribute extends BaseObject
{
    public function attributes()
    {
        return [
            'filters',
            'name',
            'rules',
            'strictMode',
            'validators',
            'value',
            'object',
        ];
    }

    protected function initAttributes($attributes)
    {
        foreach ($attributes as $name => $value) {
            $key = $this->_getAttributeKey($name);
            if (in_array($key, ['rules', 'filters', 'validators'])) {
                if ((is_array($value) && array_key_exists('class', $value)) || !is_array($value)) {
                    $value = [$value];
                }
                foreach ($value as $idx => $rule) {
                    $rule = $this->_prepareRule($rule);
                    if (
                        ($key == 'validators' && $rule instanceof ValidatorInterface) ||
                        ($key == 'filters' && $rule instanceof FilterInterface)
                    ) {
                        $attributes[$name][$idx] = $rule;
                    } else {
                        if ($rule instanceof ValidatorInterface) {
                            $attributes['validators'][] = $rule;
                        } else {
                            $attributes['filters'][] = $rule;
                        }
                        unset($attributes[$name][$idx]);
                    }
                }
            }
        }
        return $attributes;
    }

    private function _setFilters($filters)
    {
        $this->_setAttribute(
            'filters',
            array_merge_recursive(
                $this->_getAttribute('filters'),
                $filters
            )
        );
    }

    private function _setValidators($validators)
    {
        $this->_setAttribute(
            'validators',
            array_merge_recursive(
                $this->_getAttribute('validators'),
                $validators
            )
        );
    }

    private function _setFilter($filter)
    {
        $this->_setFilters([$filter]);
    }

    private function _setValidator($validator)
    {
        $this->_setValidators([$validator]);
    }

    public function setRules($rules)
    {
        $rules = $this->initAttributes(['rules' => $rules]);
        if (array_key_exists('filters', $rules)) {
            $this->_setAttribute(
                'filters',
                array_merge_recursive(
                    $this->_getAttribute('filters'),
                    $rules['filters']
                )
            );
        }
        if (array_key_exists('validators', $rules)) {
            $this->_setValidators($rules['validators']);
        }
    }

    public function setFilters($filters)
    {
        $rules = $this->initAttributes(['filters' => $filters]);
        if (array_key_exists('filters', $rules)) {
            $this->_setFilters($rules['filters']);
        }
    }

    public function setValidators($validators)
    {
        $rules = $this->initAttributes(['validators' => $validators]);
        if (array_key_exists('validators', $rules)) {
            $this->_setValidators($rules['validators']);
        }
    }

    public function setRule($rule)
    {
        $rule = $this->_prepareRule($rule);
        if ($rule instanceof FilterInterface) {
            $this->_setFilter($rule);
        } else {
            $this->_setValidator($rule);
        }
    }

    public function setFilter($filter)
    {
        $filter = $this->_prepareRule($filter);
        if ($filter instanceof FilterInterface) {
            $this->_setFilter($filter);
        }
    }

    public function setValidator($validator)
    {
        $validator = $this->_prepareRule($validator);
        if ($validator instanceof ValidatorInterface) {
            $this->_setValidator($validator);
        }
    }
    
    public function setName($name)
    {
        if (!$this->_isEmpty('name', 'name')) {
            $this->_setAttribute('name', $name, 'name');
        }
    }

    public function setObject($object)
    {
        if (!$this->_isEmpty('object', 'object')) {
            $this->_setAttribute('object', $object, 'object');
        }
    }

    /**
     * @return mixed[]
     */
    public function getRules()
    {
        return array_merge_recursive(
            $this->getValidators(),
            $this->getFilters()
        );
    }

//    /**
//     * @return BaseFilter[]
//     */
//    public function getFilters()
//    {
//        $filters = $this->_getAttribute('filters', 'filters', false);
//        return (is_array($filters) ? $filters : []);
//    }
//
//    /**
//     * @return BaseValidator[]
//     */
//    public function getValidators()
//    {
//        $validators = $this->_getAttribute('validators', 'validators', false);
//        return (is_array($validators) ? $validators : []);
//    }

    protected function _prepareRule($rule)
    {
        if (!is_object($rule)) {
            if (is_array($rule) && array_key_exists('class', $rule)) {
                $params = array_key_exists('params', $rule) ? $rule['params'] : null;
                $rule = new $rule['class']($params);
            } else {
                $rule = new DefaultFilter(['default' => $rule]);
            }
        }
        if (!($rule instanceof ValidatorInterface || $rule instanceof FilterInterface)) {
            $rule = new DefaultFilter(['default' => $rule]);
        }
        return $rule;
    }

    public function addRules($rules)
    {
        $this->setRules($rules);
    }

    public function addRule($rule)
    {
        $this->setRule($rule);
    }

    public function addFilters($filters)
    {
        $this->setFilters($filters);
    }

    public function addFilter($filter)
    {
        $this->setFilter($filter);
    }

    public function addValidators($validators)
    {
        $this->addValidators($validators);
    }

    public function addValidator($validator)
    {
        $this->addValidator($validator);
    }

    public function setValue($value)
    {
        foreach ($this->getFilters() as $filter) {
            /**
             * @var BaseFilter $filter
             */
            $value = $filter->filter($value);
        }
        foreach ($this->getValidators() as $validator) {
            /**
             * @var BaseValidator $validator
             */
            if (!$validator->isValid($value)) {
                throw new AttributeValidationFailedException(
                    $this->_getAttribute('name', 'name'),
                    $this->_getAttribute('object', 'object')
                );
            }
        }
    }

    public function set($value)
    {
        $this->setValue($value);
    }

    public function get()
    {
        return $this->_getAttribute('value', 'value');
    }

    public function getName()
    {
        return $this->_getAttribute('name', 'name');
    }
}
