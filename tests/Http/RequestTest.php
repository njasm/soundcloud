<?php

namespace Njasm\Soundcloud\Tests\Http;

use Njasm\Soundcloud\Http\Request;

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
    
    public function testGetOptions()
    {
        $this->assertArrayHasKey(CURLOPT_HEADER, $this->request->getOptions());
        $this->assertArrayNotHasKey(CURLOPT_COOKIE, $this->request->getOptions());
    }

    public function testGetBodyContent()
    {
        $expected = '{"oauth_token":"1234-ABCD"}';
        $property = new \ReflectionProperty($this->request, 'params');
        $property->setAccessible(true);
        $property->setValue($this->request, ['oauth_token' => '1234-ABCD']);

        $method = new \ReflectionMethod($this->request, 'getBodyContent');
        $method->setAccessible(true);
        $returnValue = $method->invoke($this->request);

        $this->assertEquals($expected, $returnValue);
    }

    public function testBuildDefaultHeaders()
    {
        $expected = [
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: OAuth 1234-ABCD'
        ];

        $property = new \ReflectionProperty($this->request, 'params');
        $property->setAccessible(true);
        $property->setValue($this->request, ['oauth_token' => '1234-ABCD']);

        $method = new \ReflectionMethod($this->request, 'buildDefaultHeaders');
        $method->setAccessible(true);
        $method->invoke($this->request);

        $headers = new \ReflectionProperty($this->request, 'headers');
        $headers->setAccessible(true);
        $returnvalue = $headers->getValue($this->request);

        $this->assertEquals($expected, $returnvalue);
    }
}
