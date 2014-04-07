<?php

use Njasm\Soundcloud\UrlBuilder\UrlBuilder;
use Njasm\Soundcloud\Resource\Resource;
use Njasm\Soundcloud\Auth\Auth;

class UrlBuilderTest extends \PHPUnit_Framework_TestCase 
{
    public function testGetUrlWithoutParams()
    {
        // should return https://api.soundcloud.com/me
        $auth = new Auth("ClientIDHash");
        $resource = Resource::get("/me");
        $resource->setParams(array('client_id' => $auth->getClientID()));
        $builder = new UrlBuilder($resource);
        $this->assertEquals("https://api.soundcloud.com/me?client_id=ClientIDHash", $builder->getUrl());
    }
    
    public function testGetGetUrl()
    {
        // should return https://api.soundcloud.com/tracks?q=buskers&license=cc-by-sa
        $auth = new Auth("ClientIDHash");
        $resource = Resource::get("/tracks");
        $resource->setParams(array(
            'client_id' => $auth->getClientID(),
            'q' => 'buskers',
            'license' => 'cc-by-sa'
        )); 
        
        $builder = new UrlBuilder($resource);
        $this->assertEquals("https://api.soundcloud.com/tracks?client_id=ClientIDHash&q=buskers&license=cc-by-sa", $builder->getUrl());                
    }
    
    public function testPostGetUrl()
    {    
        // should return https://www.soundcloud.com/resolve
        $auth = new Auth("ClientIDHash");
        $resource = Resource::get("/resolve");
        $resource->setParams(array(
            'client_id' => $auth->getClientID(),
            'url' => 'http://soundcloud.com/matas/hobnotropic'
        ));
        
        $builder = new UrlBuilder($resource);
        $this->assertEquals("https://api.soundcloud.com/resolve?client_id=ClientIDHash&url=http%3A%2F%2Fsoundcloud.com%2Fmatas%2Fhobnotropic", $builder->getUrl());          
    }
    
    public function testSetAndGetParams()
    {
        $auth = new Auth("ClientIDHash");
        $resource = Resource::get("/resolve", array(
            'client_id' => $auth->getClientID(),
            'q' => 'john',
            'license' => 'cc-by-sa'
        ));
    
        $builder = new UrlBuilder($resource);  
        // order does not matter
        $shoudBeArray = array(
            'client_id' => 'ClientIDHash',
            'q' => 'john',
            'license' => 'cc-by-sa'
        );
        
        $this->assertEmpty(array_merge(array_diff($shoudBeArray, $builder->getParams())));
        $builder->setParams(array('q' => 'hybrid'));
        // order does not matter
        $shoudBeArray = array('q' => 'hybrid');        
        $this->assertEmpty(array_merge(array_diff($shoudBeArray, $builder->getParams())));
    }    
    
    public function testNullSetAndGetParams()
    {
        $auth = new Auth("ClientIDHash"); 
        $builder = new UrlBuilder(Resource::get("/resolve", array()));
        $this->assertArrayNotHasKey("client_id", $builder->getParams());     
    }    
    
    public function testGetCleanPath()
    {
        $auth = new Auth("ClientIDHash");
        $builder = new UrlBuilder(Resource::get("/me/"));
        $this->assertEquals("https://api.soundcloud.com/me", $builder->getUrl());
    }
}