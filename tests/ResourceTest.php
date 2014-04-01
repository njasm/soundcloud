<?php

use Njasm\Soundcloud\Resource\Resource;

class ResourceTest extends \PHPUnit_Framework_TestCase 
{
    public function testException()
    {
        $this->setExpectedException(
            'Njasm\Soundcloud\Exception\SoundcloudException',
            "Path cannot be other then a string type and should start with a '/' (Slash)."
        );
        Resource::get();
    }
    
    public function testResourceOfTypeNotAvailableException()
    {
        $this->setExpectedException(
            'Njasm\Soundcloud\Exception\SoundcloudException',
            "Resource of type: head, not available!"
        );
        Resource::head();
    }
    
    public function testGetVerb()
    {
        $resource = Resource::get("/me");
        $this->assertEquals("get", $resource->getVerb());

        $resource = Resource::post("/me");
        $this->assertEquals("post", $resource->getVerb());
        
        $resource = Resource::put("/me");
        $this->assertEquals("put", $resource->getVerb());
        
        $resource = Resource::patch("/me");
        $this->assertEquals("patch", $resource->getVerb());
        
        $resource = Resource::delete("/me");
        $this->assertEquals("delete", $resource->getVerb());     
        
        $resource = Resource::options("/me");
        $this->assertEquals("options", $resource->getVerb());
    }
     
    public function testSetAndGetParams()
    {
        $resource = Resource::get("/tracks", array("q" => "Great Artist"));
        $this->assertArrayHasKey("q", $resource->getParams());
        $resource->setParams(array("license" => "mit"));
        $this->assertArrayHasKey("q", $resource->getParams());
        $this->assertArrayHasKey("license", $resource->getParams());        
    }
    
    public function testGetPath()
    {
        $resource = Resource::get("/tracks");
        $this->assertEquals("/tracks", $resource->getPath());
        $this->assertNotEquals("/me", $resource->getPath());
    }
}