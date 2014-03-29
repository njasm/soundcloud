<?php

use Njasm\Soundcloud\Resources\Resource;

class ResourceTest extends \PHPUnit_Framework_TestCase 
{
    /**
     * @expectedException SoundcloudException
     * @expectedExceptionMessage Path cannot be other then a string type and should start with a '/' (Slash).
     */
    public function testException()
    {
        Resource::get();
    }
    
    public function testGetVerb()
    {
        $resource = Resource::get();
        $this->assertEquals("get", $resource->getVerb());

        $resource = Resource::post();
        $this->assertEquals("post", $resource->getVerb());
        
        $resource = Resource::put();
        $this->assertEquals("put", $resource->getVerb());
        
        $resource = Resource::patch();
        $this->assertEquals("patch", $resource->getVerb());
        
        $resource = Resource::delete();
        $this->assertEquals("delete", $resource->getVerb());     
        
        $resource = Resource::options();
        $this->assertEquals("options", $resource->getVerb());
    }
}