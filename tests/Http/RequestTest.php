<?php

namespace Njasm\Soundcloud\Tests\Http;

use \Njasm\Soundcloud\Http\Request;
use \Njasm\Soundcloud\Http\Response;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    use \Njasm\Soundcloud\Tests\MocksTrait;
    use \Njasm\Soundcloud\Tests\ReflectionsTrait;

    public $request;
    
    public function setUp()
    {
        $this->request = new Request('GET', 'https://api.soundcloud.com/resolve', []);
    }
    
    public function testSetOptions()
    {
        $this->request->setOptions(array(CURLOPT_VERBOSE => true));
        $this->assertArrayHasKey(CURLOPT_VERBOSE, $this->request->options());
        $this->assertArrayHasKey(CURLOPT_RETURNTRANSFER, $this->request->options());
    }
    
    public function testGetOptions()
    {
        $this->assertArrayHasKey(CURLOPT_HEADER, $this->request->options());
        $this->assertArrayNotHasKey(CURLOPT_COOKIE, $this->request->options());
    }

    public function testGetBodyContent()
    {
        $expected = '{"oauth_token":"1234-ABCD"}';
        $property = new \ReflectionProperty($this->request, 'params');
        $property->setAccessible(true);
        $property->setValue($this->request, ['oauth_token' => '1234-ABCD']);

        $method = new \ReflectionMethod($this->request, 'bodyContent');
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

        $property = $this->reflectProperty($this->request, 'params');
        $property->setValue($this->request, ['oauth_token' => '1234-ABCD']);

        $method = new \ReflectionMethod($this->request, 'buildDefaultHeaders');
        $method->setAccessible(true);
        $method->invoke($this->request);

        $headers = $this->reflectProperty($this->request, 'headers');
        $returnvalue = $headers->getValue($this->request);

        $this->assertEquals($expected, $returnvalue);
    }

    public function testSend()
    {
        $url = 'http://127.0.0.1/me';
        $verb = 'POST';
        $factory = $this->getMock(
            'Njasm\Soundcloud\Factory\LibraryFactory',
            array('build')
        );
        $factory->expects($this->any())
            ->method('build')
            ->with($this->equalTo('ResponseInterface'))
            ->will(
                $this->returnCallback(
                    function () {
                        return new Response(
                            "HTTP/1.1 302 Found\nurl: http://127.0.0.1/index.php\r\n\r\n{\"status\": \"ok\"}",
                            array('url' => 'http://127.0.0.1/index.php'),
                            0,
                            "No Error"
                        );
                    }
                )
            );
        $request = new Request($verb, $url, [], $factory);
        $response = $request->send();
        $this->assertInstanceOf('Njasm\\Soundcloud\\Http\\ResponseInterface', $response);
    }
}
