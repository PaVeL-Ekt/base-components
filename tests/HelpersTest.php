<?php

namespace PavelEkt\BaseComponents\Tests;

use PavelEkt\BaseComponents\Helpers\ArrayHelper;
use PavelEkt\BaseComponents\Helpers\BooleanHelper;
use PavelEkt\BaseComponents\Helpers\FloatHelper;
use PavelEkt\BaseComponents\Helpers\IntegerHelper;
use PavelEkt\BaseComponents\Helpers\StringHelper;

use PavelEkt\BaseComponents\Filters\StringFilter;

class HelpersTest extends \PHPUnit_Framework_TestCase
{
    public function testArrayHelper()
    {
        $this->assertEquals(['test'], ArrayHelper::toArray('test'));
        $this->assertEquals([''], ArrayHelper::toArray(''));
        $this->assertEquals([0], ArrayHelper::toArray(0));
        $this->assertEquals([], ArrayHelper::toArray(null));
    }

    public function testBooleanHelper()
    {
        $this->assertTrue(BooleanHelper::toBool(true));
        $this->assertTrue(BooleanHelper::toBool('true'));
        $this->assertTrue(BooleanHelper::toBool('text'));
        $this->assertTrue(BooleanHelper::toBool(1));
        $this->assertTrue(BooleanHelper::toBool(new \stdClass()));
        $this->assertTrue(BooleanHelper::toBool(['test']));

        $this->assertFalse(BooleanHelper::toBool(false));
        $this->assertFalse(BooleanHelper::toBool('false'));
        $this->assertFalse(BooleanHelper::toBool(0));
        $this->assertFalse(BooleanHelper::toBool([]));
        $this->assertFalse(BooleanHelper::toBool(''));
    }

    public function testFloatHelper()
    {
        $this->assertEquals(0.1, FloatHelper::toFloat(0.1));
        $this->assertEquals(0.1, FloatHelper::toFloat('0.1'));
        $this->assertEquals(1, FloatHelper::toFloat(['test']));
        $this->assertEquals(0, FloatHelper::toFloat(false));
    }

    public function testIntegerHelper()
    {
        $this->assertEquals(0, IntegerHelper::toInt(0.1));
        $this->assertEquals(0, IntegerHelper::toInt('0.1'));
        $this->assertEquals(1, IntegerHelper::toInt(['test']));
        $this->assertEquals(0, integerHelper::toInt(false));
    }

    public function testStringHelper()
    {
        $this->assertTrue(StringHelper::checkEncoding('utf8'));
        $this->assertFalse(StringHelper::checkEncoding('SomeWrongEncoding'));

        // strCrop test
        $this->assertEquals('Hello W...', StringHelper::strCrop('Hello World !!!', 10, StringHelper::STR_CROP_RIGHT));
        $this->assertEquals('...rld !!!', StringHelper::strCrop('Hello World !!!', 10, StringHelper::STR_CROP_LEFT));
        $this->assertEquals('...Worl...', StringHelper::strCrop('Hello World !!!', 10, StringHelper::STR_CROP_BOTH));

        // strPad test
        $this->assertEquals('Hello******', StringHelper::strPad('Hello', 11, '*', STR_PAD_RIGHT));
        $this->assertEquals('******Hello', StringHelper::strPad('Hello', 11, '*', STR_PAD_LEFT));
        $this->assertEquals('***Hello***', StringHelper::strPad('Hello', 11, '*', STR_PAD_BOTH));
        $this->assertEquals('***Hello!**', StringHelper::strPad('Hello!', 11, '*', STR_PAD_BOTH));
        $this->assertEquals('==>Hello<==', StringHelper::strPad('Hello', 11, '<===>', STR_PAD_BOTH));

        // strRPad test
        $this->assertEquals('<==Hello==>', StringHelper::strRPad('Hello', 11, '<===>', STR_PAD_BOTH));

        // strReplace test
        $this->assertEquals('Hello World!!!', StringHelper::strReplace('Hello $1!!!', '$1', 'World'));
        $this->assertEquals('Hello World! Hello World!', StringHelper::strReplace('Hello $1! Hello $1!', '$1', 'World'));

        // printVar test
        $this->assertEquals('\'Hello World!\'', StringHelper::printVar('Hello World!'));
        $this->assertEquals('0.1', StringHelper::printVar(0.1));
        $this->assertEquals('true', StringHelper::printVar(true));
        $this->assertEquals('0', StringHelper::printVar(0));
        $this->assertEquals('[\'test\'=>\'Hello\',0=>\'World!\']', StringHelper::printVar(['test' => 'Hello', 'World!']));
        $this->assertEquals('Object(stdClass){}', StringHelper::printVar(new \stdClass()));
        $this->assertEquals(
            'Object(PavelEkt\BaseComponents\Filters\StringFilter){\'cropType\'=>2, \'default\'=>\'\', \'maxLength\'=>NULL, \'minLength\'=>0, \'padString\'=>\' \', \'padType\'=>1}',
            StringHelper::printVar(new StringFilter())
        );

        // toStr test
        $this->assertEquals('Hello World!', StringHelper::toStr('Hello World!'));
        $this->assertEquals('0', StringHelper::toStr(0));
        $this->assertEquals('0.1', StringHelper::toStr(0.1));
        $this->assertEquals('true', StringHelper::toStr(true));
        $this->assertEquals('[\'test\'=>\'Hello\',0=>\'World!\']', StringHelper::printVar(['test' => 'Hello', 'World!']));
        $this->assertEquals('Object(stdClass){}', StringHelper::printVar(new \stdClass()));
    }
}
