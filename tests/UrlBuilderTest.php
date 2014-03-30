<?php

use Njasm\Soundcloud\UrlBuilder\UrlBuilder;

class UrlBuilderTest extends \PHPUnit_Framework_TestCase 
{
    public function testGetUrlWithoutParams()
    {
        // should return https://api.soundcloud.com/me
        $builder = new UrlBuilder("/me", array(), "get", "api");
        $this->assertEquals("https://api.soundcloud.com/me", $builder->getUrl());
    }
    
    public function testGetGetUrl()
    {
        // should return https://api.soundcloud.com/tracks?q=buskers&license=cc-by-sa
        $builder = new UrlBuilder("/tracks", array('q' => 'buskers', 'license' => 'cc-by-sa'), "get", "api");
        $this->assertEquals("https://api.soundcloud.com/tracks?q=buskers&license=cc-by-sa", $builder->getUrl());                
    }
    
    public function testPostGetUrl()
    {    
        // should return https://www.soundcloud.com/resolve
        $builder = new UrlBuilder("/resolve", array('q' => 'john', 'license' => 'cc-by-sa'), "post", "www");
        $this->assertEquals("https://www.soundcloud.com/resolve", $builder->getUrl());          
    }
    
    public function testSetAndGetQuery()
    {
        $builder = new UrlBuilder("/resolve", array('q' => 'john', 'license' => 'cc-by-sa'), "post", "www");
        $this->assertEquals("q=john&license=cc-by-sa", $builder->getQuery());
        
        $query = array(
            'q' => 'hybrid',
        );
        $builder->setQuery($query);
        $this->assertEquals("q=hybrid", $builder->getQuery());
    }    
    
    public function testNullSetAndGetQuery()
    {
        $builder = new UrlBuilder("/resolve", array(), "post", "www");
        $this->assertNull($builder->getQuery());     
    }       
}