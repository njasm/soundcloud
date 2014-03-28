<?php

use Njasm\Soundcloud\UrlBuilder\UrlBuilder;

class UrlBuilderTest extends \PHPUnit_Framework_TestCase 
{
    public function testGetUri()
    {
        // should return https://api.soundcloud.com/me
        $builder = new UrlBuilder("/me", array(), "get", "api");
        $this->assertEquals("https://api.soundcloud.com/me", $builder->getUrl());
    }
}