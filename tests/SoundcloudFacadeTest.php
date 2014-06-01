<?php

namespace Njasm\Soundcloud\Tests;

use Njasm\Soundcloud\Resource\Resource;
use Njasm\Soundcloud\UrlBuilder\UrlBuilder;
use Njasm\Soundcloud\Request\Request;
use Njasm\Soundcloud\Request\Response;

class SoundcloudFacadeTest extends \PHPUnit_Framework_TestCase
{
    protected $soundcloud;
    
    public function setUp()
    {
        $this->soundcloud = new \Njasm\Soundcloud\SoundcloudFacade(
            "ClientIDHash", "TheSecretHash", "http://example.com/soundcloud"
        );
    }
    
    public function testAccessToAuthValues()
    {
        $this->assertEquals("ClientIDHash", $this->soundcloud->getAuthClientID());
    }
    
    public function testGetAuthUrl()
    {
        $expected = "https://www.soundcloud.com/connect?client_id=ClientIDHash&scope="
            . "non-expiring&display=popup&response_type=code&redirect_uri=http%3A%2F%2Fexample.com%2Fsoundcloud&state=";
        $this->assertEquals($expected, $this->soundcloud->getAuthUrl());
    }
    
    
    public function testCodeForToken()
    {
        // request Factory mock
        $reqFactoryMock = $this->getMock(
            "Njasm\\Soundcloud\\Factory\\Factory",
            array('make')
        );
        $reqFactoryMock->expects($this->any())
            ->method('make')
            ->with($this->equalTo('ResponseInterface'))
            ->will(
                $this->returnCallback(
                    function ($arg) {
                        return new Response(
                            "Content-Type: application/json\r\nurl: http://127.0.0.1/index.php\r\n\r\n{\"access_token\": \"1234567890\"}",
                            array('url' => 'http://127.0.0.1/index.php'),
                            0,
                            "No Error"
                        );
                    }
                )
            );
            
        // soundcloud Factory mock
        $factoryMock = $this->getMock(
            "Njasm\\Soundcloud\\Factory\\Factory",
            array('make')
        );
        $factoryMock->expects($this->any())
            ->method('make')
            ->with(
                $this->logicalOr(
                    $this->equalTo('UrlBuilderInterface'),
                    $this->equalTo('RequestInterface'),
                    $this->equalTo('ResourceInterface')
                )
            )->will(
                $this->returnCallback(
                    function ($arg) use (&$reqFactoryMock) {
                        if ($arg == 'UrlBuilderInterface') {
                            return new UrlBuilder(new Resource('get', '/index.php'), "127", "0.0.1", "http://");
                        } elseif ($arg == 'RequestInterface') {
                            return new Request(
                                new Resource('get', '/index.php'),
                                new UrlBuilder(new Resource('get', '/index.php'), "127", "0.0.1", "http://"),
                                $reqFactoryMock
                            );
                        } elseif ($arg == 'ResourceInterface') {
                            return new Resource('get', '/index.php');
                        }
                    }
                )
            );
        
        $property = $this->reflectProperty("Njasm\\Soundcloud\\Soundcloud", "factory");
        $property->setAccessible(true);
        $property->setValue($this->soundcloud, $factoryMock);
        $response = $this->soundcloud->codeForToken("FakeCode");

        $this->assertInstanceOf('Njasm\Soundcloud\Request\ResponseInterface', $response);
        $this->assertEquals('{"access_token": "1234567890"}', $response->bodyRaw());
        $this->assertEquals("1234567890", $this->soundcloud->getAuthToken());
    }
       
    public function testUserCredentialsFlow()
    {
        // request Factory mock
        $reqFactoryMock = $this->getMock(
            "Njasm\\Soundcloud\\Factory\\Factory",
            array('make')
        );
        $reqFactoryMock->expects($this->any())
            ->method('make')
            ->with($this->equalTo('ResponseInterface'))
            ->will(
                $this->returnCallback(
                    function ($arg) {
                        return new Response(
                            "Content-Type: application/json\r\nurl: http://127.0.0.1/index.php\r\n\r\n{\"access_token\": \"1234567890\"}",
                            array('url' => 'http://127.0.0.1/index.php'),
                            0,
                            "No Error"
                        );
                    }
                )
            );
            
        // soundcloud Factory mock
        $factoryMock = $this->getMock(
            "Njasm\\Soundcloud\\Factory\\Factory",
            array('make')
        );
        $factoryMock->expects($this->any())
            ->method('make')
            ->with(
                $this->logicalOr(
                    $this->equalTo('UrlBuilderInterface'),
                    $this->equalTo('RequestInterface'),
                    $this->equalTo('ResourceInterface')
                )
            )->will(
                $this->returnCallback(
                    function ($arg) use (&$reqFactoryMock) {
                        if ($arg == 'UrlBuilderInterface') {
                            return new UrlBuilder(new Resource('get', '/index.php'), "127", "0.0.1", "http://");
                        } elseif ($arg == 'RequestInterface') {
                            return new Request(
                                new Resource('get', '/index.php'),
                                new UrlBuilder(new Resource('get', '/index.php'), "127", "0.0.1", "http://"),
                                $reqFactoryMock
                            );
                        } elseif ($arg == 'ResourceInterface') {
                            return new Resource('get', '/index.php');
                        }
                    }
                )
            );
                
        $property = $this->reflectProperty("Njasm\\Soundcloud\\Soundcloud", "factory");
        $property->setAccessible(true);
        $property->setValue($this->soundcloud, $factoryMock);
        $response = $this->soundcloud->userCredentials("FakeUser", "FakePassword");

        $this->assertInstanceOf('Njasm\Soundcloud\Request\ResponseInterface', $response);
        $this->assertEquals('{"access_token": "1234567890"}', $response->bodyRaw());
    }

    public function testDownload()
    {
        // request Factory mock
        $reqFactoryMock = $this->getMock(
            "Njasm\\Soundcloud\\Factory\\Factory",
            array('make')
        );
        $reqFactoryMock->expects($this->any())
            ->method('make')
            ->with($this->equalTo('ResponseInterface'))
            ->will(
                $this->returnCallback(
                    function ($arg) {
                        return new Response(
                            "Content-Type: application/octet-stream\r\nLocation: http://127.0.0.1/the_track.mp3\r\n\r\nBIG_DATA_TRACK",
                            array('url' => 'http://127.0.0.1/index.php'),
                            0,
                            "No Error"
                        );
                    }
                )
            );
            
        // soundcloud Factory mock
        $factoryMock = $this->getMock(
            "Njasm\\Soundcloud\\Factory\\Factory",
            array('make')
        );
        $factoryMock->expects($this->any())
            ->method('make')
            ->with(
                $this->logicalOr(
                    $this->equalTo('UrlBuilderInterface'),
                    $this->equalTo('RequestInterface'),
                    $this->equalTo('ResourceInterface')
                )
            )->will(
                $this->returnCallback(
                    function ($arg) use (&$reqFactoryMock) {
                        if ($arg == 'UrlBuilderInterface') {
                            return new UrlBuilder(new Resource('get', '/index.php'), "127", "0.0.1", "http://");
                        } elseif ($arg == 'RequestInterface') {
                            return new Request(
                                new Resource('get', '/index.php'),
                                new UrlBuilder(new Resource('get', '/index.php'), "127", "0.0.1", "http://"),
                                $reqFactoryMock
                            );
                        } elseif ($arg == 'ResourceInterface') {
                            return new Resource('get', '/index.php');
                        }
                    }
                )
            );
                
        $property = $this->reflectProperty("Njasm\\Soundcloud\\Soundcloud", "factory");
        $property->setAccessible(true);
        $property->setValue($this->soundcloud, $factoryMock);
        $response = $this->soundcloud->download(123, true);

        $this->assertInstanceOf('Njasm\Soundcloud\Request\ResponseInterface', $response);
        $this->assertEquals('BIG_DATA_TRACK', $response->bodyRaw());
        
        $response = $this->soundcloud->download(123);
        $this->assertEquals('http://127.0.0.1/the_track.mp3', $response->getHeader('Location'));
    }
    
    public function testUpload()
    {
        // request Factory mock
        $reqFactoryMock = $this->getMock(
            "Njasm\\Soundcloud\\Factory\\Factory",
            array('make')
        );
        $reqFactoryMock->expects($this->any())
            ->method('make')
            ->with($this->equalTo('ResponseInterface'))
            ->will(
                $this->returnCallback(
                    function ($arg) {
                        return new Response(
                            "Content-Type: application/json\r\nurl: http://127.0.0.1/the_track.mp3\r\n\r\nSUCCESS_UPLOAD",
                            array('url' => 'http://127.0.0.1/index.php'),
                            0,
                            "No Error"
                        );
                    }
                )
            );
            
        // soundcloud Factory mock
        $factoryMock = $this->getMock(
            "Njasm\\Soundcloud\\Factory\\Factory",
            array('make')
        );
        $factoryMock->expects($this->any())
            ->method('make')
            ->with(
                $this->logicalOr(
                    $this->equalTo('UrlBuilderInterface'),
                    $this->equalTo('RequestInterface'),
                    $this->equalTo('ResourceInterface')
                )
            )->will(
                $this->returnCallback(
                    function ($arg) use (&$reqFactoryMock) {
                        if ($arg == 'UrlBuilderInterface') {
                            return new UrlBuilder(new Resource('post', '/tracks'), "127", "0.0.1", "http://");
                        } elseif ($arg == 'RequestInterface') {
                            return new Request(
                                new Resource('post', '/tracks'),
                                new UrlBuilder(new Resource('post', '/tracks'), "127", "0.0.1", "http://"),
                                $reqFactoryMock
                            );
                        } elseif ($arg == 'ResourceInterface') {
                            return new Resource('post', '/tracks');
                        }
                    }
                )
            );
                
        $property = $this->reflectProperty("Njasm\\Soundcloud\\Soundcloud", "factory");
        $property->setAccessible(true);
        $property->setValue($this->soundcloud, $factoryMock);
        
        $filePath = __DIR__ . 'bootstrap.php';
        $response = $this->soundcloud->upload($filePath)->bodyRaw();
        
        $this->assertEquals("SUCCESS_UPLOAD", $response);
    }
        
    /**
     * Helper method for properties reflection testing.
     */
    private function reflectProperty($class, $property)
    {
        $property = new \ReflectionProperty($class, $property);
        $property->setAccessible(true);
        
        return $property;
    }
    
    private function reflectMethod($class, $method)
    {
        $method = new \ReflectionMethod($class, $method);
        $method->setAccessible(true);
        
        return $method;
    }    
}
