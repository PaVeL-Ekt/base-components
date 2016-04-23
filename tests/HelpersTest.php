<?php

namespace PavelEkt\BaseComponents\Tests;

use PavelEkt\BaseComponents\Filters\StringFilter;
use PavelEkt\BaseComponents\Helpers\StringHelper;

class HelpersTest extends \PHPUnit_Framework_TestCase
{
    public function testStringHelper()
    {
        //
        $this->assertTrue(StringHelper::checkEncoding('utf8'));
        $this->assertFalse(StringHelper::checkEncoding('SomeWrongEncoding'));

        // strCrop test
        $this->assertEquals('Hello W...', StringHelper::strCrop('Hello World !!!', 10, StringHelper::STR_CROP_RIGHT));
        $this->assertEquals('...rld !!!', StringHelper::strCrop('Hello World !!!', 10, StringHelper::STR_CROP_LEFT));
        $this->assertEquals('...rld ...', StringHelper::strCrop('Hello World !!!', 10, StringHelper::STR_CROP_BOTH));

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
