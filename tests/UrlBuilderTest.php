<?php

use Njasm\Soundcloud\UrlBuilder\UrlBuilder;

class UrlBuilderTest extends \PHPUnit_Framework_TestCase 
{
    public function testGetUrl()
    {
        // should return https://api.soundcloud.com/me
        $builder = new UrlBuilder("/me", array(), "get", "api");
        $this->assertEquals("https://api.soundcloud.com/me", $builder->getUrl());
        
        // should return https://api.soundcloud.com/tracks?q=buskers&license=cc-by-sa
        $builder = new UrlBuilder("/tracks", array('q' => 'buskers', 'license' => 'cc-by-sa'), "get", "api");
        $this->assertEquals("https://api.soundcloud.com/tracks?q=buskers&license=cc-by-sa", $builder->getUrl());        
    }
}