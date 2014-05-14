<?php

namespace Njasm\Soundcloud\Tests;

class ResourceTest extends \PHPUnit_Framework_TestCase
{
    private $factory;
    
    public function setUp()
    {
        $this->factory = new \Njasm\Soundcloud\Factory\Factory();
    }
    
    public function testException()
    {
        $this->setExpectedException(
            'Njasm\Soundcloud\Exception\SoundcloudException',
            "Path cannot be other then a string type and should start with a '/' (Slash)."
        );
        $this->factory->make('ResourceInterface', array('get'));
    }
    
    public function testResourceOfTypeNotAvailableException()
    {
        $this->setExpectedException(
            'Njasm\Soundcloud\Exception\SoundcloudException',
            "Resource of type: head, not available!"
        );
        $this->factory->make('ResourceInterface', array('head'));
    }
    
    public function testGetVerb()
    {
        $resource = $this->factory->make('ResourceInterface', array('get', '/me'));
        $this->assertEquals("get", $resource->getVerb());

        $resource = $this->factory->make('ResourceInterface', array('post', '/me'));
        $this->assertEquals("post", $resource->getVerb());
        
        $resource = $this->factory->make('ResourceInterface', array('put', '/me'));
        $this->assertEquals("put", $resource->getVerb());
        
        $resource = $this->factory->make('ResourceInterface', array('patch', '/me'));
        $this->assertEquals("patch", $resource->getVerb());

        $resource = $this->factory->make('ResourceInterface', array('delete', '/me'));
        $this->assertEquals("delete", $resource->getVerb());
        
        $resource = $this->factory->make('ResourceInterface', array('options', '/me'));
        $this->assertEquals("options", $resource->getVerb());
    }
     
    public function testSetAndGetParams()
    {
        $resource = $this->factory->make('ResourceInterface', array('get', '/track', array('q' => 'Great Artist')));
        $this->assertArrayHasKey("q", $resource->getParams());
        $resource->setParams(array("license" => "mit"));
        $this->assertArrayHasKey("q", $resource->getParams());
        $this->assertArrayHasKey("license", $resource->getParams());
    }
    
    public function testGetPath()
    {
        $resource = $this->factory->make('ResourceInterface', array('get', '/tracks'));
        $this->assertEquals("/tracks", $resource->getPath());
        $this->assertNotEquals("/me", $resource->getPath());
    }
}
