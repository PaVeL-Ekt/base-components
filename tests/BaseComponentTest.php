<?php

namespace PavelEkt\BaseComponents\Tests;

use PavelEkt\BaseComponents\Filters\StringFilter;
use PHPUnit_Framework_TestCase;

class BaseComponentTest extends PHPUnit_Framework_TestCase
{
    protected $stub;

    protected function setUp()
    {
        parent::setUp();
        $this->stub = $this->getMockForAbstractClass('AbstractClass');
        $this->stub->expects($this->any())
            ->method('extendedAttributes')
            ->will($this->returnValue([
                'default' => 100,
                'text' => new StringFilter(['maxLength' => 10]),
            ]));
    }

    public function testAttributes()
    {
        $this->assertObjectHasAttribute('default', $this->stub);
        $this->assertEquals(100, $this->stub->default);
    }

    public function testFilterAttributes()
    {
        $this->assertObjectHasAttribute('text', $this->stub);
        $this->stub->text = 'Hello world !!!';
        $this->assertEquals('Hello w...', $this->stub->text);
    }
}
