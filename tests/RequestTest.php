<?php

use Njasm\Soundcloud\Request\Request;
use Njasm\Soundcloud\UrlBuilder\UrlBuilder;
use Njasm\Soundcloud\Resource\Resource;
use Njasm\Soundcloud\Factory\Factory;


class RequestTest extends \PHPUnit_Framework_TestCase 
{
    public $resource;
    public $urlBuilder;
    public $request;
    
    public function setUp()
    {
        $this->resource = new Resource('get', '/resolve');
        $this->urlBuilder = new UrlBuilder($this->resource);
        $this->request = new Request($this->resource, $this->urlBuilder, new Factory());
    }
    
    public function testSetOptions()
    {
        $this->request->setOptions(array(CURLOPT_VERBOSE => true));
        $this->assertArrayHasKey(CURLOPT_VERBOSE, $this->request->getOptions());
        $this->assertArrayHasKey(CURLOPT_RETURNTRANSFER, $this->request->getOptions());
    }
    
    public function testAsJsonAsXml()
    {
        $property = new ReflectionProperty("Njasm\\Soundcloud\\Request\\Request", "responseFormat");
        $property->setAccessible(true);
        
        $this->request->asJson();
        $this->assertEquals("application/json", $property->getValue($this->request));
        $this->request->asXml();
        $this->assertEquals("application/xml", $property->getValue($this->request));    
    }
    
    public function testGetOptions()
    {
        $this->assertArrayHasKey(CURLOPT_HEADER, $this->request->getOptions());
        $this->assertArrayNotHasKey(CURLOPT_COOKIE, $this->request->getOptions());
    }
}