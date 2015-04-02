<?php

namespace Njasm\Soundcloud\Tests\Exception;

use \Njasm\Soundcloud\Exception\SoundcloudResponseException;

class SoundcloudResponseExceptionTest extends \PHPUnit_Framework_TestCase
{
    protected $exception;

    public function setUp()
    {
        $errors = ["errors" => [
            ["error_message" => "Message One"],
            ["error_message" => "Message Two"]
        ]];

        $this->exception = new SoundcloudResponseException($errors);
    }

    public function testGetCode()
    {
        $this->assertEquals(0, $this->exception->getCode());
    }

    public function testGetErrorsAsArray()
    {
        $expected = [
            ["error_message" => "Message One"],
            ["error_message" => "Message Two"]
        ];

        $this->assertEquals($expected, $this->exception->getErrorsAsArray());
    }

    public function testGetErrorsAsString()
    {
        $expected = "Message One.Message Two";
        $delimiter = ".";

        $this->assertEquals($expected, $this->exception->getErrorsAsString($delimiter));
    }
}
