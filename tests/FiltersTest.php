<?php

namespace PavelEkt\BaseComponents\Tests;

use PavelEkt\BaseComponents\Filters\BooleanFilter;
use PavelEkt\BaseComponents\Filters\DefaultFilter;
use PavelEkt\BaseComponents\Filters\FloatFilter;
use PavelEkt\BaseComponents\Filters\IntegerFilter;
use PavelEkt\BaseComponents\Filters\RegexpFilter;
use PavelEkt\BaseComponents\Filters\StringFilter;
use PavelEkt\BaseComponents\Filters\UrlFilter;
use PavelEkt\BaseComponents\Filters\EmailFilter;

class FiltersTest extends \PHPUnit_Framework_TestCase
{
//    public function testBooleanFilter()
//    {
//        $filter = new BooleanFilter();
//        $this->assertTrue($filter->filter(true));
//        $this->assertFalse($filter->filter(false));
//        $this->assertTrue($filter->filter('text'));
//        $this->assertTrue($filter->filter('true'));
//        $this->assertFalse($filter->filter('false'));
//        $this->assertTrue($filter->filter(1));
//        $this->assertFalse($filter->filter(0));
//        $this->assertFalse($filter->filter(null));
//    }
//
//    public function testDefaultFilter()
//    {
//        $filter = new DefaultFilter(['default' => 'test']);
//        $this->assertEquals('test', $filter->filter(null));
//        $this->assertEquals('', $filter->filter(''));
//        $this->assertEquals('data', $filter->filter('data'));
//    }
//
//    public function testFloatFilter()
//    {
//        $filter = new FloatFilter();
//        $this->assertEquals(0.1, $filter->filter(0.1));
//        $this->assertEquals(0.1, $filter->filter('0.1'));
//        $this->assertEquals(0, $filter->filter(null));
//    }
//
//    public function testIntegerFilter()
//    {
//        $filter = new IntegerFilter();
//        $this->assertEquals(0, $filter->filter(0.1));
//        $this->assertEquals(0, $filter->filter('0'));
//        $this->assertEquals(0, $filter->filter('0.1'));
//        $this->assertEquals(0, $filter->filter(null));
//    }
//
//    public function testRegexpFilter()
//    {
//        $filter = new RegexpFilter(['pattern'=>'/\$1/', 'replacement'=>'World']);
//        $this->assertEquals('Hello World! The World is mine.', $filter->filter('Hello $1! The $1 is mine.'));
//    }
//
//    public function testStringFilter()
//    {
//        $filter = new StringFilter([]);
//        $this->assertEquals('', $filter->filter(null));
//        $this->assertEquals('test', $filter->filter('test'));
//        $this->assertEquals('0.1', $filter->filter(0.1));
//        $this->assertEquals('Object(stdClass){}', $filter->filter(new \stdClass()));
//        $filter = new StringFilter(['padType'=>STR_PAD_BOTH, 'padString' => '<======>', 'minLength' => 10]);
//        $this->assertEquals('==>test<==', $filter->filter('test'));
//    }
//
//    public function testUrlFilter()
//    {
//        $filter = new UrlFilter();
//        $this->assertEquals('http://yandex.ru', $filter->filter('http://yandex.ru'));
//        //  Negative tests
//        $this->assertEquals(null, $filter->filter('http:/yandex.ru'));
//    }
//
//    public function testEmailFilter()
//    {
//        $filter = new EmailFilter();
//        $this->assertEquals('pavel-ekt@yandex.ru', $filter->filter('pavel-ekt@yandex.ru'));
//        // Negative tests
//        $this->assertEquals(null, $filter->filter('pavel-ekt.yandex.ru'));
//    }
}
