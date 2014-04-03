<?php

use Njasm\Soundcloud\UrlBuilder\UrlBuilder;
use Njasm\Soundcloud\Resource\ResourceInterface;
use Njasm\Soundcloud\Auth\Auth;

class UrlBuilderTest extends \PHPUnit_Framework_TestCase 
{
    public function testGetUrlWithoutParams()
    {
        // should return https://api.soundcloud.com/me
        $auth = new Auth("ClientIDHash");
        $builder = new UrlBuilder(Resource::get("/me"), $auth);
        $this->assertEquals("https://api.soundcloud.com/me", $builder->getUrl());
    }
    
    public function testGetGetUrl()
    {
        // should return https://api.soundcloud.com/tracks?q=buskers&license=cc-by-sa
        $auth = new Auth("ClientIDHash");
        $builder = new UrlBuilder(Resource::get("/me", array('q' => 'buskers', 'license' => 'cc-by-sa')), $auth);
        $this->assertEquals("https://api.soundcloud.com/tracks?q=buskers&license=cc-by-sa", $builder->getUrl());                
    }
    
    public function testPostGetUrl()
    {    
        // should return https://www.soundcloud.com/resolve
        $auth = new Auth("ClientIDHash");
        $builder = new UrlBuilder(Resource::get("/resolve", array('q' => 'john', 'license' => 'cc-by-sa')), $auth);
        $this->assertEquals("https://www.soundcloud.com/resolve", $builder->getUrl());          
    }
    
    public function testSetAndGetQuery()
    {
        $auth = new Auth("ClientIDHash");
        $builder = new UrlBuilder(Resource::get("/resolve", array('q' => 'john', 'license' => 'cc-by-sa')), $auth, "post", "www");
        $this->assertEquals("q=john&license=cc-by-sa", $builder->getQuery());
        
        $query = array(
            'q' => 'hybrid',
        );
        $builder->setQuery($query);
        $this->assertEquals("q=hybrid", $builder->getQuery());
    }    
    
    public function testNullSetAndGetQuery()
    {
        $auth = new Auth("ClientIDHash");        
        $builder = new UrlBuilder("/resolve", array(), $auth, "post", "www");
        $this->assertNull($builder->getQuery());     
    }       
}