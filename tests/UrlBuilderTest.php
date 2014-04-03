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
        $builder = new UrlBuilder(Resource::get("/me"), $auth);
        $this->assertEquals("https://api.soundcloud.com/me?client_id=ClientIDHash", $builder->getUrl());
    }
    
    public function testGetGetUrl()
    {
        // should return https://api.soundcloud.com/tracks?q=buskers&license=cc-by-sa
        $auth = new Auth("ClientIDHash");
        $builder = new UrlBuilder(Resource::get("/tracks", array('q' => 'buskers', 'license' => 'cc-by-sa')), $auth);
        $this->assertEquals("https://api.soundcloud.com/tracks?client_id=ClientIDHash&q=buskers&license=cc-by-sa", $builder->getUrl());                
    }
    
    public function testPostGetUrl()
    {    
        // should return https://www.soundcloud.com/resolve
        $auth = new Auth("ClientIDHash");
        $builder = new UrlBuilder(Resource::get("/resolve", array('url' => 'http://soundcloud.com/matas/hobnotropic')), $auth);
        $this->assertEquals("https://api.soundcloud.com/resolve?client_id=ClientIDHash&url=http%3A%2F%2Fsoundcloud.com%2Fmatas%2Fhobnotropic", $builder->getUrl());          
    }
    
    public function testSetAndGetQuery()
    {
        $auth = new Auth("ClientIDHash");
        $builder = new UrlBuilder(Resource::get("/resolve", array('q' => 'john', 'license' => 'cc-by-sa')), $auth);
        $this->assertEquals("client_id=ClientIDHash&q=john&license=cc-by-sa", $builder->getQuery());
        
        $query = array(
            'q' => 'hybrid',
        );
        $builder->setQuery($query);
        $this->assertEquals("client_id=ClientIDHash&q=hybrid", $builder->getQuery());
    }    
    
    public function testNullSetAndGetQuery()
    {
        $auth = new Auth("ClientIDHash");        
        $builder = new UrlBuilder(Resource::get("/resolve", array()), $auth, "post", "www");
        $this->assertEquals("client_id=ClientIDHash", $builder->getQuery());     
    }       
}