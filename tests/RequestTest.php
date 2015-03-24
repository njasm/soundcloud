<?php

namespace Njasm\Soundcloud\Tests;

use Njasm\Soundcloud\Http\Request;
use Njasm\Soundcloud\Http\Url\UrlBuilder;
use Njasm\Soundcloud\Resource\Resource;
use Njasm\Soundcloud\Factory\Factory;
use Njasm\Soundcloud\Http\Response;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public $request;
    
    public function setUp()
    {
        $this->request = new Request('GET', 'https://api.soundcloud.com/resolve', []);
    }
    
    public function testSetOptions()
    {
        $this->request->setOptions(array(CURLOPT_VERBOSE => true));
        $this->assertArrayHasKey(CURLOPT_VERBOSE, $this->request->getOptions());
        $this->assertArrayHasKey(CURLOPT_RETURNTRANSFER, $this->request->getOptions());
    }
    
    public function testAsJson()
    {
        $property = new \ReflectionProperty("Njasm\\Soundcloud\\Http\\Request", "responseFormat");
        $property->setAccessible(true);

        $this->assertEquals("application/json", $property->getValue($this->request));
    }
    
    public function testGetOptions()
    {
        $this->assertArrayHasKey(CURLOPT_HEADER, $this->request->getOptions());
        $this->assertArrayNotHasKey(CURLOPT_COOKIE, $this->request->getOptions());
    }
}
