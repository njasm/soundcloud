<?php

namespace Njasm\Soundcloud\Tests;

use Njasm\Soundcloud\Request\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public $response;
    public $info;
    public $errno;
    public $errorString;

    public $responseObj;

    public function setUp(): void
    {
        //example curl response
        $this->response = "HTTP/1.1 302 Found
            Access-Control-Expose-Headers: Date
            X-Runtime: 18
            Age: 0
            Content-Length: 98
            X-Cacheable: NO:Cache-Control=no-cache
            Location: https://api.soundcloud.com/users/1492543?consumer_key=apigee
            Access-Control-Allow-Methods: GET, PUT, POST, DELETE
            Server: nginx
            X-Cache: MISS
            Cache-Control: no-cache
            X-Varnish: 1958699183
            Access-Control-Allow-Headers: Accept, Authorization, Content-Type, Origin
            Vary: Accept-Encoding
            Date: Sun, 13 Apr 2014 18:09:52 GMT
            Access-Control-Allow-Origin: *
            Via: 1.1 varnish
            Content-Type: application/json; charset=utf-8\r\n\r\n
          {\"status\": \"302 - Found\",\"location\": \"https://api.soundcloud.com/users/1492543?consumer_key=apigee\"}";

        $this->info = array('curlInfo' => 'array');
        $this->errno = 0;
        $this->errorString = "";

        $this->responseObj = new Response($this->response, $this->info, $this->errno, $this->errorString);
    }

    public function testGetHttpCode()
    {
        $this->assertEquals("302", $this->responseObj->getHttpCode());
    }

    public function testGetHttpVersion()
    {
        $this->assertEquals("HTTP/1.1", $this->responseObj->getHttpVersion());
    }

    public function testGetInfo()
    {
        $this->assertArrayHasKey("curlInfo", $this->responseObj->getInfo());
    }

    public function testGetHeaders()
    {
        $this->assertArrayHasKey("X-Varnish", $this->responseObj->getHeaders());
    }

    public function testGetHeader()
    {
        $value = $this->responseObj->getHeader("X-Varnish");
        $this->assertEquals("1958699183", $value);
    }

    public function testNullGetHeader()
    {
        $this->assertNull($this->responseObj->getHeader("Non-Existent-Head-Param"));
    }

    public function testHasHeader()
    {
        $this->assertTrue($this->responseObj->hasHeader("X-Varnish"));
        $this->assertFalse($this->responseObj->hasHeader("No-Header-Key"));
    }

    public function testBodyRaw()
    {
        $property = new \ReflectionProperty("\\Njasm\\Soundcloud\\Request\\Response", "body");
        $property->setAccessible(true);
        $property->setValue(
            $this->responseObj,
            '{"status": "302 - Found","location": "https://api.soundcloud.com/users/1492543?consumer_key=apigee"}'
        );
        $this->assertEquals(
            '{"status": "302 - Found","location": "https://api.soundcloud.com/users/1492543?consumer_key=apigee"}',
            $this->responseObj->bodyRaw()
        );
    }

    public function testBodyObjectAsJson()
    {
        $property = new \ReflectionProperty("\\Njasm\\Soundcloud\\Request\\Response", "body");
        $property->setAccessible(true);
        $property->setValue(
            $this->responseObj,
            '{"status": "302 - Found","location": "https://api.soundcloud.com/users/1492543?consumer_key=apigee"}'
        );

        $this->assertTrue(
            true && stripos($this->responseObj->getHeader('Content-Type'), 'application/json') !== false
        );
        $this->assertInstanceOf('\stdClass', $this->responseObj->bodyObject());
    }

    public function testbodyObjectException()
    {
        $property = new \ReflectionProperty("\\Njasm\\Soundcloud\\Request\\Response", "headers");
        $property->setAccessible(true);
        $property->setValue(
            $this->responseObj,
            array('Content-Type' => 'wrong/content; charset=utf-8\r\n\r\n')
        );

        $this->expectException('\OutOfBoundsException');
        $this->expectExceptionMessage("Last Request Content-Type isn't application/json.");

        $this->responseObj->bodyObject();
    }

    public function testBodyArrayJson()
    {
        $property = new \ReflectionProperty("\\Njasm\\Soundcloud\\Request\\Response", "body");
        $property->setAccessible(true);
        $property->setValue(
            $this->responseObj,
            '{"status": "302 - Found","location": "https://api.soundcloud.com/users/1492543?consumer_key=apigee"}'
        );

        $this->assertInternalType('array', $this->responseObj->bodyArray());
    }

    public function testBodyArrayXml()
    {
        $property = new \ReflectionProperty("\\Njasm\\Soundcloud\\Request\\Response", "headers");
        $property->setAccessible(true);
        $property->setValue(
            $this->responseObj,
            array('Content-Type' => 'application/json; charset=utf-8\r\n\r\n')
        );

        $property = new \ReflectionProperty("\\Njasm\\Soundcloud\\Request\\Response", "body");
        $property->setAccessible(true);
        $property->setValue($this->responseObj, '{"status": "302 - Found","location": "https://api.soundcloud.com/users/1492543?consumer_key=apigee"}');

        $this->assertInternalType('array', $this->responseObj->bodyArray());
    }
}
