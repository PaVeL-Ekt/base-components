<?php

namespace PavelEkt\BaseComponents\Tests;

use PavelEkt\BaseComponents\Abstracts\BaseAttribute;
use PavelEkt\BaseComponents\Filters\DefaultFilter;
use PavelEkt\BaseComponents\Filters\StringFilter;

use PavelEkt\BaseComponents\Validators\EmailValidator;
use PHPUnit_Framework_TestCase;

class BaseAttributeTest extends PHPUnit_Framework_TestCase
{
    protected $stub;

    protected function setUp()
    {
        parent::setUp();
        $this->stub = $this->getMockForAbstractClass(BaseAttribute::className(), [], '', false, true, true, ['attributes']);
        $this->stub->expects($this->any())
            ->method('attributes')
            ->will($this->returnValue([
                'default' => 100,
                'test1' => ['filter' => StringFilter::className(), 'params' => ['maxLength' => 10]],
                'text' => new StringFilter(['maxLength' => 10]),
                'data' => [
                    new DefaultFilter(['default' => 'Hello world!']),
                    new StringFilter(['maxLength' => 10]),
                ],
                'data1' => [
                    new DefaultFilter(['default' => 'Hello world!']),
                    ['filter' => StringFilter::className(), 'params' => ['maxLength' => 10]],
                ]
            ]));
    }

    public function test_Create()
    {
        $stub = $this->getMockForAbstractClass(BaseAttribute::className(), [], '', false);
        $stub->__construct(
            'attr',
            null,
            [
                [new DefaultFilter(['default' => 'base@example.com'])],
                ['class' => 'PavelEkt\BaseComponents\Validators\EmailValidator']
            ]
        );
        $this->assertEquals('attr', $stub->getName());
        $this->assertEquals('base@example.com', $stub->get());
    }

    public function testAttributes()
    {
        $this->assertEquals(100, $this->stub->default);
    }

    public function testFilterAttributes()
    {
        $this->stub->default = 300;
        $this->assertEquals(300, $this->stub->default);

        $this->stub->default = null;
        $this->assertEquals(100, $this->stub->default);

        $this->stub->text = 'Hello world !!!';
        $this->assertEquals('Hello w...', $this->stub->text);

        $this->stub->test1 = 'Hello world !!!';
        $this->assertEquals('Hello w...', $this->stub->text);

        $this->stub->data = null;
        $this->assertEquals('Hello w...', $this->stub->text);

        $this->stub->data1 = null;
        $this->assertEquals('Hello w...', $this->stub->text);
    }
}
