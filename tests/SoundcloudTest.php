<?php

namespace Njasm\Soundcloud\Tests;

use \Njasm\Soundcloud\Factory\LibraryFactory;
use \Njasm\Soundcloud\Soundcloud;


class SoundcloudTest extends \PHPUnit_Framework_TestCase
{
    public $soundcloud;
    protected $requestClass = 'Njasm\Soundcloud\Http\Request';

    public function setUp()
    {
        $clientID = "ClientIDHash";
        $clientSecret = "ClientSecretHash";
        $uriCallback = "http://example.com/soundcloud";
        $this->soundcloud = new Soundcloud($clientID, $clientSecret, $uriCallback);
    }

    public function testGetAuthUrl()
    {
        $expected = 'https://soundcloud.com/connect?client_id=ClientIDHash&scope=non-expiring&display=popup&response_type=code&redirect_uri=http%3A%2F%2Fexample.com%2Fsoundcloud&state=';
        $this->assertEquals($expected, $this->soundcloud->getAuthUrl());
    }

    public function testGet()
    {
        $this->assertInstanceOf($this->requestClass, $this->soundcloud->get('/me'));
    }

    public function testPut()
    {
        $this->assertInstanceOf($this->requestClass, $this->soundcloud->put('/me'));
    }

    public function testPost()
    {
        $this->assertInstanceOf($this->requestClass, $this->soundcloud->post('/me'));
    }

    public function testDelete()
    {
        $this->assertInstanceOf($this->requestClass, $this->soundcloud->delete('/me'));
    }

    public function testOptions()
    {
        $this->assertInstanceOf($this->requestClass, $this->soundcloud->options('/me'));
    }

    public function testGetCurlFile()
    {
        $method = new \ReflectionMethod($this->soundcloud, 'getCurlFile');
        $method->setAccessible(true);

        if (class_exists('\CurlFile')) {
            $this->assertInstanceOf('\CurlFile', $method->invoke($this->soundcloud, __FILE__));
        } else {
            $expected = '@' . __FILE__;
            $this->assertEquals($expected, $method->invoke($this->soundcloud, __FILE__));
        }
    }

//    public function testRequest()
//    {
//        // request ApiResponseFactory mock
//        $reqFactoryMock = $this->getMock(
//            "Njasm\\Soundcloud\\ApiResponseFactory\\ApiResponseFactory",
//            array('make')
//        );
//        $reqFactoryMock->expects($this->any())
//            ->method('make')
//            ->with($this->equalTo('ResponseInterface'))
//            ->will(
//                $this->returnCallback(
//                    function ($arg) {
//                        return new Response(
//                            "HTTP/1.1 302 Found\nurl: http://127.0.0.1/index.php\r\n\r\nDummy Response Body",
//                            array('url' => 'http://127.0.0.1/index.php'),
//                            0,
//                            "No Error"
//                        );
//                    }
//                )
//            );
//
//        // soundcloud ApiResponseFactory mock
//        $factoryMock = $this->getMock(
//            "Njasm\\Soundcloud\\ApiResponseFactory\\ApiResponseFactory",
//            array('make')
//        );
//        $factoryMock->expects($this->any())
//            ->method('make')
//            ->with(
//                $this->logicalOr(
//                    $this->equalTo('UrlBuilderInterface'),
//                    $this->equalTo('RequestInterface')
//                )
//            )->will(
//                $this->returnCallback(
//                    function ($arg) use (&$reqFactoryMock) {
//                        if ($arg == 'UrlBuilderInterface') {
//                            return new UrlBuilder(new Resource('get', '/index.php'), "127", "0.0.1", "http://");
//                        } elseif ($arg == 'RequestInterface') {
//                            return new Request(
//                                new Resource('get', '/index.php'),
//                                new UrlBuilder(new Resource('get', '/index.php'), "127", "0.0.1", "http://"),
//                                $reqFactoryMock
//                            );
//                        }
//                    }
//                )
//            );
//
//        $property = $this->reflectProperty("Njasm\\Soundcloud\\Soundcloud", "factory");
//        $property->setAccessible(true);
//        $property->setValue($this->soundcloud, $factoryMock);
//        $response = $this->soundcloud->request();
//
//        $this->assertInstanceOf('Njasm\Soundcloud\Request\ResponseInterface', $response);
//        $this->assertEquals("Dummy Response Body", $response->bodyRaw());
//        // coverage, already tested inside Request class
//        $this->soundcloud->request(array(CURLOPT_RETURNTRANSFER => true));
//    }

    /**
     * Auth tests.
     */
    public function testGetAuthClientID()
    {
        $this->assertEquals("ClientIDHash", $this->soundcloud->auth()->getClientID());
    }

    public function testNulledGetAuthToken()
    {
        $this->assertNull($this->soundcloud->auth()->getToken());
    }

    public function testNulledGetAuthScope()
    {
        $this->assertNull($this->soundcloud->auth()->getScope());
    }

    public function testNullGetExpires()
    {
        $this->assertNull($this->soundcloud->auth()->getExpires());
    }

    public function testGetCurlResponse()
    {
        $this->assertNull($this->soundcloud->getCurlResponse());
    }

    /**
     * Helper method for properties reflection testing.
     */
    private function reflectProperty($class, $property)
    {
        $property = new \ReflectionProperty($class, $property);
        $property->setAccessible(true);

        return $property;
    }

    private function reflectMethod($class, $method)
    {
        $method = new \ReflectionMethod($class, $method);
        $method->setAccessible(true);

        return $method;
    }

    private function getResponseMock()
    {
        $responseMock = $this->getMock("Njasm\\Soundcloud\\Http\\Response", array('bodyObject'));
        $responseMock->expects($this->any())
            ->method('bodyObject')
            ->will(
                $this->returnCallback(
                    function () {
                        $stdClass = new \stdClass();
                        $stdClass->oauth_token = "12345-ABCD";
                        $stdClass->refresh_token = "54321-DCBA";
                        $stdClass->scope = "non-expiring";
                        return $stdClass;
                    }
                )
            );

        return $responseMock;
    }

    private function getRequestMock()
    {
        $requestMock = $this->getMock("Njasm\\Soundcloud\\Http\\Request", array('send'));
        $requestMock->expects($this->any())
            ->method('send')
            ->will(
                $this->returnCallback(
                    function () {
                        return LibraryFactory::build('ResponseInterface');
                    }
                )
            );

        return $requestMock;
    }
}
