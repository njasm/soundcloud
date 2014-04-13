<?php

use Njasm\Soundcloud\Request\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase 
{
    public $response;
    public $info;
    public $errno;
    public $errorString;
    
    public $responseObj;
    
    public function setUp()
    {
        $this->response = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "curlResponseJson");
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
    
    public function testHasHeader()
    {
        $this->assertTrue($this->responseObj->hasHeader("X-Varnish"));
        $this->assertFalse($this->responseObj->hasHeader("No-Header-Key"));
    }
    
    public function testGetBody()
    {
        $property = new ReflectionProperty("\\Njasm\\Soundcloud\\Request\\Response", "body");
        $property->setAccessible(true);
        $property->setValue($this->responseObj, '{"status": "302 - Found","location": "https://api.soundcloud.com/users/1492543?consumer_key=apigee"}');
        $this->assertEquals(
            '{"status": "302 - Found","location": "https://api.soundcloud.com/users/1492543?consumer_key=apigee"}',
            $this->responseObj->getBody()
        );
    }
}

