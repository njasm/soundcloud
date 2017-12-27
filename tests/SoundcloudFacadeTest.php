<?php

namespace Njasm\Soundcloud\Tests;

use Njasm\Container\Container;
use Njasm\Soundcloud\Request\RequestInterface;
use Njasm\Soundcloud\Request\ResponseInterface;
use Njasm\Soundcloud\Resource\Resource;
use Njasm\Soundcloud\Resource\ResourceInterface;
use Njasm\Soundcloud\SoundcloudFacade;
use Njasm\Soundcloud\UrlBuilder\UrlBuilder;
use Njasm\Soundcloud\Request\Request;
use Njasm\Soundcloud\Request\Response;
use Njasm\Soundcloud\UrlBuilder\UrlBuilderInterface;
use PHPUnit\Framework\TestCase;

class SoundcloudFacadeTest extends TestCase
{
    /** @var SoundcloudFacade */
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
        $this->setContainerMock();
        $response = $this->soundcloud->codeForToken("FakeCode");

        $this->assertInstanceOf('Njasm\Soundcloud\Request\ResponseInterface', $response);
        $this->assertEquals('{"access_token": "1234567890"}', $response->bodyRaw());
        $this->assertEquals("1234567890", $this->soundcloud->getAuthToken());
    }
    
    public function testRefreshAccessToken()
    {
        $this->setContainerMock();
        $response = $this->soundcloud->refreshAccessToken();

        $this->assertInstanceOf('Njasm\Soundcloud\Request\ResponseInterface', $response);
        $this->assertEquals('{"access_token": "1234567890"}', $response->bodyRaw());
        $this->assertEquals("1234567890", $this->soundcloud->getAuthToken());        
    }
    
    public function testUserCredentialsFlow()
    {
        $this->setContainerMock();
        $response = $this->soundcloud->userCredentials("FakeUser", "FakePassword");

        $this->assertInstanceOf('Njasm\Soundcloud\Request\ResponseInterface', $response);
        $this->assertEquals('{"access_token": "1234567890"}', $response->bodyRaw());
    }

    public function testDownload()
    {
        $factoryMock = $this->createMock(Container::class);
        $factoryMock->expects($this->any())
            ->method('get')
            ->with(
                $this->logicalOr(
                    $this->equalTo(UrlBuilderInterface::class),
                    $this->equalTo(RequestInterface::class),
                    $this->equalTo(ResourceInterface::class),
                    $this->equalTo(ResponseInterface::class)
                )
            )->will(
                $this->returnCallback(
                    function ($arg) use (&$factoryMock) {
                        if ($arg == UrlBuilderInterface::class) {
                            return new UrlBuilder(new Resource('get', '/index.php'), "127", "0.0.1", "http://");
                        } elseif ($arg == RequestInterface::class) {
                            return new Request(
                                new Resource('get', '/index.php'),
                                new UrlBuilder(new Resource('get', '/index.php'), "127", "0.0.1", "http://"),
                                $factoryMock
                            );
                        } elseif ($arg == ResourceInterface::class) {
                            return new Resource('get', '/index.php');
                        } elseif ($arg == ResponseInterface::class) {
                            return new Response(
                                "HTTP/1.1 302 Found\nContent-Type: application/octet-stream\r\nLocation: http://127.0.0.1/the_track.mp3\r\n\r\nBIG_DATA_TRACK",
                                array('url' => 'http://127.0.0.1/index.php'),
                                0,
                                "No Error"
                            );
                        }

                    }
                )
            );

        $property = $this->reflectProperty("Njasm\\Soundcloud\\Soundcloud", "container");
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
        $factoryMock = $this->createMock(Container::class);
        $factoryMock->expects($this->any())
            ->method('get')
            ->with(
                $this->logicalOr(
                    $this->equalTo(UrlBuilderInterface::class),
                    $this->equalTo(RequestInterface::class),
                    $this->equalTo(ResourceInterface::class),
                    $this->equalTo(ResponseInterface::class)
                )
            )->will(
                $this->returnCallback(
                    function ($arg) use (&$factoryMock) {
                        if ($arg == UrlBuilderInterface::class) {
                            return new UrlBuilder(new Resource('get', '/index.php'), "127", "0.0.1", "http://");
                        } elseif ($arg == RequestInterface::class) {
                            return new Request(
                                new Resource('get', '/index.php'),
                                new UrlBuilder(new Resource('get', '/index.php'), "127", "0.0.1", "http://"),
                                $factoryMock
                            );
                        } elseif ($arg == ResourceInterface::class) {
                            return new Resource('get', '/index.php');
                        } elseif ($arg == ResponseInterface::class) {
                            return new Response(
                                "HTTP/1.1 302 Found\nContent-Type: application/json\r\nurl: http://127.0.0.1/the_track.mp3\r\n\r\nSUCCESS_UPLOAD",
                                array('url' => 'http://127.0.0.1/index.php'),
                                0,
                                "No Error"
                            );
                        }

                    }
                )
            );

        $property = $this->reflectProperty("Njasm\\Soundcloud\\Soundcloud", "container");
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

    private function setContainerMock()
    {
        $factoryMock = $this->createMock(Container::class);
        $factoryMock->expects($this->any())
            ->method('get')
            ->with(
                $this->logicalOr(
                    $this->equalTo(UrlBuilderInterface::class),
                    $this->equalTo(RequestInterface::class),
                    $this->equalTo(ResourceInterface::class),
                    $this->equalTo(ResponseInterface::class)
                )
            )->will(
                $this->returnCallback(
                    function ($arg) use (&$factoryMock) {
                        if ($arg == UrlBuilderInterface::class) {
                            return new UrlBuilder(new Resource('get', '/index.php'), "127", "0.0.1", "http://");
                        } elseif ($arg == RequestInterface::class) {
                            return new Request(
                                new Resource('get', '/index.php'),
                                new UrlBuilder(new Resource('get', '/index.php'), "127", "0.0.1", "http://"),
                                $factoryMock
                            );
                        } elseif ($arg == ResourceInterface::class) {
                            return new Resource('get', '/index.php');
                        } elseif ($arg == ResponseInterface::class) {
                            return new Response(
                                "HTTP/1.1 302 Found\nContent-Type: application/json\r\nurl: http://127.0.0.1/index.php\r\n\r\n{\"access_token\": \"1234567890\"}",
                                array('url' => 'http://127.0.0.1/index.php'),
                                0,
                                "No Error"
                            );
                        }

                    }
                )
            );

        $property = $this->reflectProperty("Njasm\\Soundcloud\\Soundcloud", "container");
        $property->setAccessible(true);
        $property->setValue($this->soundcloud, $factoryMock);
    }
}
