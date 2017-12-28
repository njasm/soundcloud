<?php

namespace Njasm\Soundcloud\Tests;

use Njasm\Container\Container;
use Njasm\Soundcloud\Request\Request;
use Njasm\Soundcloud\Request\RequestInterface;
use Njasm\Soundcloud\Request\ResponseInterface;
use Njasm\Soundcloud\Resource\ResourceInterface;
use Njasm\Soundcloud\UrlBuilder\UrlBuilder;
use Njasm\Soundcloud\Resource\Resource;

use Njasm\Soundcloud\Request\Response;
use Njasm\Soundcloud\UrlBuilder\UrlBuilderInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class RequestTest extends TestCase
{
    /** @var ResourceInterface */
    public $resource;
    /** @var UrlBuilderInterface */
    public $urlBuilder;
    /** @var RequestInterface */
    public $request;
    
    public function setUp()
    {
        $this->resource = new Resource('get', '/resolve');
        $this->urlBuilder = new UrlBuilder($this->resource);
        $this->request = new Request($this->resource, $this->urlBuilder, new Container());
    }
    
    public function testSetOptions()
    {
        $this->request->setOptions(array(CURLOPT_VERBOSE => true));
        $this->assertArrayHasKey(CURLOPT_VERBOSE, $this->request->getOptions());
        $this->assertArrayHasKey(CURLOPT_RETURNTRANSFER, $this->request->getOptions());
    }
    
    public function testAsJson()
    {
        $property = new \ReflectionProperty("Njasm\\Soundcloud\\Request\\Request", "responseFormat");
        $property->setAccessible(true);
        
        $this->request->asJson();
        $this->assertEquals("application/json", $property->getValue($this->request));
    }
    
    public function testGetOptions()
    {
        $this->assertArrayHasKey(CURLOPT_HEADER, $this->request->getOptions());
        $this->assertArrayNotHasKey(CURLOPT_COOKIE, $this->request->getOptions());
    }
    
    public function testRequest()
    {
        $container = new Container();

        $resource = new Resource('get', '/index.php');
        $url = new UrlBuilder($resource, "127", "0.0.1", "http://");
        $request = new Request($resource, $url, $container);
        $response = new Response(
            "HTTP/1.1 302 Found\nurl: http://127.0.0.1/index.php\r\n\r\nDummy Response Body",
            ['url' => 'http://127.0.0.1/index.php'],0,"No Error"
        );

        $container->singleton(ContainerInterface::class, $container);
        $container->singleton(UrlBuilderInterface::class, $url);
        $container->singleton(ResponseInterface::class, $response);
        $container->singleton(RequestInterface::class, $request);

        $request = new Request($resource, $url, $container);
        $response = $request->exec();
        
        $this->assertInstanceOf('Njasm\\Soundcloud\\Request\\ResponseInterface', $response);
    }

    public function testUserAgentString()
    {
        $name = \Njasm\Soundcloud\Soundcloud::LIB_NAME;
        $version = \Njasm\Soundcloud\Soundcloud::VERSION;
        $url = \Njasm\Soundcloud\Soundcloud::LIB_URL;

        $expected = 'Mozilla/5.0 (compatible; ' . $name . '/' . $version . '; +' . $url . ')';
        $returned = $this->request->getUserAgent();

        $this->assertEquals($expected, $returned);
    }
}
