<?php

use Njasm\Soundcloud\Resources\Resource;

class ResourceTest extends \PHPUnit_Framework_TestCase 
{
    public function testException()
    {
        $this->setExpectedException(
            'Njasm\Soundcloud\Exceptions\SoundcloudException',
            "Path cannot be other then a string type and should start with a '/' (Slash)."
        );
        Resource::get();
    }
    
    public function testResourceOfTypeNotAvailableException()
    {
        $this->setExpectedException(
            'Njasm\Soundcloud\Exceptions\SoundcloudException',
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
}