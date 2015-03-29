<?php

namespace Njasm\Soundcloud\Tests\Http\Url;

use Njasm\Soundcloud\Http\Url\UrlBuilder;

class UrlBuilderTest extends \PHPUnit_Framework_TestCase
{

    public function testGetUrlWithoutParams()
    {
        $verb = 'GET';
        $url = 'https://api.soundcloud.com/me';
        $params['client_id'] = 'ClientIDHash';

        $this->assertEquals("https://api.soundcloud.com/me?client_id=ClientIDHash", UrlBuilder::getUrl($verb, $url, $params));
    }
    
    public function testGetFullUrl()
    {
        // should return https://api.soundcloud.com/tracks?q=buskers&license=cc-by-sa
        $verb = 'GET';
        $url = 'https://api.soundcloud.com/tracks';
        $params = [
            'client_id' => 'ClientIDHash',
            'q' => 'buskers',
            'license' => 'cc-by-sa'
        ];

        $this->assertEquals(
            "https://api.soundcloud.com/tracks?client_id=ClientIDHash&q=buskers&license=cc-by-sa",
            UrlBuilder::getUrl($verb, $url, $params)
        );
    }

    public function testCleanPath()
    {
        $verb = 'GET';
        $params['client_id'] = 'ClientIDHash';
        $url = '/me/'; // notice the trailing slash

        $finalUrl = UrlBuilder::getUrl($verb, $url, $params);
        $this->assertEquals("https://api.soundcloud.com/me?client_id=ClientIDHash", $finalUrl);
    }

    public function testOnlyResource()
    {
        $verb = 'GET';
        $params['client_id'] = 'ClientIDHash';
        $url = '/me';

        $finalUrl = UrlBuilder::getUrl($verb, $url, $params);
        $this->assertEquals("https://api.soundcloud.com/me?client_id=ClientIDHash", $finalUrl);
    }

    public function testBogusResource()
    {
        $verb = 'GET';
        $params['client_id'] = 'ClientIDHash';
        $url = 'me';

        $finalUrl = UrlBuilder::getUrl($verb, $url, $params);
        $this->assertEquals("https://api.soundcloud.com/me?client_id=ClientIDHash", $finalUrl);
    }

    public function testSetAndGetBasePath()
    {
        $verb = 'GET';
        $url = "http://www.localhost";
        $path = '/me';
        UrlBuilder::setBaseUrl($url);

        $this->assertEquals($url, UrlBuilder::getBaseUrl());
        $this->assertEquals("http://www.localhost/me", UrlBuilder::getUrl($verb, $path));
    }
}
