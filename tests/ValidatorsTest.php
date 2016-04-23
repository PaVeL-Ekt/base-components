<?php

namespace PavelEkt\BaseComponents\Tests;

use PavelEkt\BaseComponents\Validators\EmailValidator;
use PavelEkt\BaseComponents\Validators\UrlValidator;

class ValidatorsTest extends \PHPUnit_Framework_TestCase
{
    public function testEmailValidator()
    {
        $this->assertTrue(EmailValidator::isValid('PaVeL-Ekt@yandex.ru'));
        $this->assertTrue(EmailValidator::isValid('PaVeL-Ekt@narod.ya.ru'));
        $this->assertTrue(EmailValidator::isValid('PaVeL.Ekt@yandex.ru'));
        // Negative
        $this->assertFalse(EmailValidator::isValid(''));
        $this->assertFalse(EmailValidator::isValid('PaVeL-Ekt.yandex.ru'));
        $this->assertFalse(EmailValidator::isValid('PaVeL-Ekt@'));
        $this->assertFalse(EmailValidator::isValid('@yandex.ru'));
    }

    public function testUrlValidator()
    {
        $this->assertTrue(UrlValidator::isValid('yandex.ru'));
        $this->assertTrue(UrlValidator::isValid('www.yandex.ru'));
        $this->assertTrue(UrlValidator::isValid('http://yandex.ru'));
        $this->assertTrue(UrlValidator::isValid('https://yandex.ru'));
        $this->assertTrue(UrlValidator::isValid('//yandex.ru'));
        $this->assertTrue(UrlValidator::isValid('yandex.ru?search=test&comment=test'));
        $this->assertTrue(UrlValidator::isValid('/index.php/test?data=1'));
        $this->assertTrue(UrlValidator::isValid('/path/to/page/?data=1'));
        // Negative
        $this->assertFalse(UrlValidator::isValid('http:/yandex.ru'));
        $this->assertFalse(UrlValidator::isValid('http://yandex/'));
        $this->assertFalse(UrlValidator::isValid(''));
    }
}
