<?php

namespace PavelEkt\BaseComponents\Tests;

use PavelEkt\BaseComponents\Abstracts\BaseObject;
use PHPUnit_Framework_TestCase;
use PavelEkt\BaseComponents\Exceptions\AttributeNotFoundException;

class BaseObjectTest extends PHPUnit_Framework_TestCase
{
    protected $stub;

    protected function setUp()
    {
        parent::setUp();
        $this->stub = $this->getMockForAbstractClass(BaseObject::className(), [], '', false, true, true, ['attributes']);
        $this->stub->expects($this->any())
            ->method('attributes')
            ->will($this->returnValue([
                'default' => 'text',
                'text1',
                'text2',
            ]));
        $this->stub->__construct(['text1' => 'Hello world!!!']);
    }

    public function testAttributes()
    {
        $this->assertTrue($this->stub->hasAttribute('default'));
        $this->assertTrue($this->stub->hasAttribute('text1'));
        $this->assertTrue($this->stub->hasAttribute('text2'));
        $this->assertFalse($this->stub->hasAttribute('text3'));

        $this->assertEquals('text', $this->stub->default);
        $this->assertEquals('Hello world!!!', $this->stub->text1);
        $this->assertNull($this->stub->text2);

        $this->assertEquals('Hello world!!!', $this->stub->getAttribute('text1'));
        $this->assertNull($this->stub->getAttribute('text2'));

        $this->setExpectedException(AttributeNotFoundException::getClass());
        $this->stub->text3;

        $this->assertEquals([
            'default' => 'text',
            'text1' => 'Hello world!!!',
            'text2' => null
        ], $this->stub->getAttributes());

        $this->stub->text1 = null;
        $this->stub->setAttribute('text2', 'Hello world!!!');

        $this->assertEquals([
            'default' => 'text',
            'text1' => null,
            'text2' => 'Hello world!!!'
        ], $this->stub->getAttributes());

        $this->setExpectedException(AttributeNotFoundException::getClass());
        $this->stub->text3 = null;

        $attributes = [
            'default' => 'Hello',
            'text1' => 'world',
            'text2' => '!!!'
        ];
        $this->stub->setAttributes($attributes);

        $this->assertEquals($attributes, $this->stub->getAttributes());
    }
}
