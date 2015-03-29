<?php

namespace Njasm\Soundcloud\Tests\Http;

use Njasm\Soundcloud\Http\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public $request;
    
    public function setUp()
    {
        $this->request = new Request('GET', 'https://api.soundcloud.com/resolve', []);
    }
    
    public function testSetOptions()
    {
        $this->request->setOptions(array(CURLOPT_VERBOSE => true));
        $this->assertArrayHasKey(CURLOPT_VERBOSE, $this->request->getOptions());
        $this->assertArrayHasKey(CURLOPT_RETURNTRANSFER, $this->request->getOptions());
    }
    
    public function testGetOptions()
    {
        $this->assertArrayHasKey(CURLOPT_HEADER, $this->request->getOptions());
        $this->assertArrayNotHasKey(CURLOPT_COOKIE, $this->request->getOptions());
    }
}
