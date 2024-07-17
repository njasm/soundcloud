<?php

namespace Njasm\Soundcloud\Tests;

use Njasm\Container\Container;
use Njasm\Soundcloud\Request\RequestInterface;
use Njasm\Soundcloud\Request\ResponseInterface;
use Njasm\Soundcloud\Resource\Resource;
use Njasm\Soundcloud\UrlBuilder\UrlBuilder;
use Njasm\Soundcloud\Factory\Factory;
use Njasm\Soundcloud\Request\Request;
use Njasm\Soundcloud\Soundcloud;
use Njasm\Soundcloud\Request\Response;
use Njasm\Soundcloud\UrlBuilder\UrlBuilderInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class SoundcloudTest extends TestCase
{
    /** @var Soundcloud */
    public $soundcloud;
    
    public function setUp(): void
    {
        $clientID = "ClientIDHash";
        $clientSecret = "ClientSecretHash";
        $uriCallback = "http://example.com/soundcloud";
        $this->soundcloud = new Soundcloud($clientID, $clientSecret, $uriCallback);
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

        $this->soundcloud->get('/test');

        $property = $this->reflectProperty("Njasm\\Soundcloud\\Soundcloud", "container");
        $property->setAccessible(true);
        $property->setValue($this->soundcloud, $container);
        $response = $this->soundcloud->request();

        $this->assertInstanceOf('Njasm\Soundcloud\Request\ResponseInterface', $response);
        $this->assertEquals("Dummy Response Body", $response->bodyRaw());
        // coverage, already tested inside Request class
        $this->soundcloud->request([CURLOPT_RETURNTRANSFER => true]);
    }

    /**
     * Auth tests.
     */
    public function testGetAuthClientID()
    {
        $this->assertEquals("ClientIDHash", $this->soundcloud->getAuthClientID());
    }
    
    public function testNulledGetAuthToken()
    {
        $this->assertNull($this->soundcloud->getAuthToken());
    }
    
    public function testNulledGetAuthScope()
    {
        $this->assertNull($this->soundcloud->getAuthScope());
    }
    
    public function testNullGetExpires()
    {
        $this->assertNull($this->soundcloud->getExpires());
    }
    
    /**
     * Resources tests.
     */
    public function testGetResourceCreation()
    {
        $property = $this->reflectProperty("Njasm\\Soundcloud\\Soundcloud", "resource");
        $this->soundcloud->get('/resolve');
        $this->assertTrue($property->getValue($this->soundcloud) instanceof Resource);
        $this->assertEquals("get", $property->getValue($this->soundcloud)->getVerb());
    }

    public function testPostResourceCreation()
    {
        $property = $this->reflectProperty("Njasm\\Soundcloud\\Soundcloud", "resource");
        $this->soundcloud->post('/resolve');
        $this->assertTrue($property->getValue($this->soundcloud) instanceof Resource);
        $this->assertEquals("post", $property->getValue($this->soundcloud)->getVerb());
    }
    
    public function testPutResourceCreation()
    {
        $property = $this->reflectProperty("Njasm\\Soundcloud\\Soundcloud", "resource");
        $this->soundcloud->put('/resolve');
        $this->assertTrue($property->getValue($this->soundcloud) instanceof \Njasm\Soundcloud\Resource\ResourceInterface);
        $this->assertEquals("put", $property->getValue($this->soundcloud)->getVerb());
    }
    
    public function testDeleteResourceCreation()
    {
        $property = $this->reflectProperty("Njasm\\Soundcloud\\Soundcloud", "resource");
        $this->soundcloud->delete('/resolve');
        $this->assertTrue($property->getValue($this->soundcloud) instanceof Resource);
        $this->assertEquals("delete", $property->getValue($this->soundcloud)->getVerb());
    }
    
    public function testSetParams()
    {
        $params = array(
            'url' => 'http://www.soundcloud.com/hybrid-species'
        );
        $property = $this->reflectProperty("Njasm\\Soundcloud\\Soundcloud", "resource");
        $this->soundcloud->get('/resolve');
        $this->soundcloud->setParams($params);
        $this->assertArrayHasKey('url', $property->getValue($this->soundcloud)->getParams());
    }

    public function testNoResourceException()
    {
        $this->expectException('\RuntimeException');
        $this->expectExceptionMessage(
            "No Resource found. you must call a http verb method before Njasm\Soundcloud\Soundcloud::setParams"
        );
        
        $facade = $this->soundcloud->setParams(array('url' => 'http://www.soundcloud.com/hybrid-species'));
    }
    
    public function testAsJson()
    {
        $property = $this->reflectProperty("Njasm\\SoundCloud\\Soundcloud", "responseFormat");
        $this->soundcloud->asJson();
        $this->assertEquals("json", $property->getValue($this->soundcloud));
    }
    
    public function testMergeAuthParams()
    {
        $method = $this->reflectMethod("Njasm\\Soundcloud\\Soundcloud", "mergeAuthParams");
        $params = $method->invoke($this->soundcloud, array(), false);
        $this->assertArrayHasKey("client_id", $params);
        $this->assertArrayNotHasKey("oauth_token", $params);
                
        $params = $method->invoke($this->soundcloud, array(), true);
        $this->assertArrayHasKey("client_secret", $params);
    }
    
    /**
     * Code Coverage
     */
    public function testSetResponseFormat()
    {
        $reqMock = $this->createMock(
            "Njasm\\Soundcloud\\Request\\Request",
            array('asJson'),
            array(new Resource('get', '/resolve'), new UrlBuilder(new Resource('get', '/resolve')), new Factory())
        );
        $reqMock->expects($this->any())->method('asJson');
        
        $method = $this->reflectMethod("Njasm\\Soundcloud\\Soundcloud", "setResponseFormat");

        $this->soundcloud->asXml();
        $method->invoke($this->soundcloud, $reqMock);
        $this->soundcloud->asJson();
        $method->invoke($this->soundcloud, $reqMock);

        $this->assertTrue(true);
    }
    
    public function testGetCurlResponse()
    {
        $this->assertNull($this->soundcloud->getCurlResponse());
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
