<?php

namespace Njasm\Soundcloud\Tests;

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
    
    public function testGetGetUrl()
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
}
