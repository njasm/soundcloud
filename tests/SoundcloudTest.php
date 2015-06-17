<?php

namespace Njasm\Soundcloud\Tests;

use \Njasm\Soundcloud\Soundcloud;


class SoundcloudTest extends \PHPUnit_Framework_TestCase
{
    use MocksTrait;
    use ReflectionsTrait;

    /** @var Soundcloud */
    public $soundcloud;
    protected $requestClass = 'Njasm\Soundcloud\Http\Request';

    public function setUp()
    {
        $clientID = "ClientIDHash";
        $clientSecret = "ClientSecretHash";
        $uriCallback = "http://example.com/soundcloud";
        $this->soundcloud = new Soundcloud($clientID, $clientSecret, $uriCallback);
    }

    public function testGetAuthUrl()
    {
        $expected = 'https://soundcloud.com/connect?client_id=ClientIDHash&scope=non-expiring&display=popup&response_type=code&redirect_uri=http%3A%2F%2Fexample.com%2Fsoundcloud&state=';
        $this->assertEquals($expected, $this->soundcloud->authUrl());
    }

    public function testGet()
    {
        $this->assertInstanceOf($this->requestClass, $this->soundcloud->get('/me'));
    }

    public function testPut()
    {
        $this->assertInstanceOf($this->requestClass, $this->soundcloud->put('/me'));
    }

    public function testPost()
    {
        $this->assertInstanceOf($this->requestClass, $this->soundcloud->post('/me'));
    }

    public function testDelete()
    {
        $this->assertInstanceOf($this->requestClass, $this->soundcloud->delete('/me'));
    }

    public function testOptions()
    {
        $this->assertInstanceOf($this->requestClass, $this->soundcloud->options('/me'));
    }

    public function testGetCurlFile()
    {
        $method = new \ReflectionMethod($this->soundcloud, 'curlFile');
        $method->setAccessible(true);

        if (class_exists('\CurlFile')) {
            $this->assertInstanceOf('\CurlFile', $method->invoke($this->soundcloud, __FILE__));
        } else {
            $expected = '@' . __FILE__;
            $this->assertEquals($expected, $method->invoke($this->soundcloud, __FILE__));
        }
    }

    public function testGetAuthClientID()
    {
        $this->assertEquals("ClientIDHash", $this->soundcloud->auth()->clientID());
    }

    public function testNulledGetAuthToken()
    {
        $this->assertNull($this->soundcloud->auth()->token());
    }

    public function testNulledGetAuthScope()
    {
        $this->assertNull($this->soundcloud->auth()->scope());
    }

    public function testNullGetExpires()
    {
        $this->assertNull($this->soundcloud->auth()->expires());
    }

    public function testGetCurlResponse()
    {
        $this->assertNull($this->soundcloud->lastResponse());
    }

    public function testGetMe()
    {
        $expected = '\Njasm\Soundcloud\Resource\User';
        $data = include __DIR__ . '/Data/Serialized_User.php';
        $response = $this->getResponseMock('bodyRaw', function() use ($data) { return $data; });
        $request = $this->getRequestMock($response);
        $factory = $this->getFactoryMock($request, $response);
        $reflectedFactory = $this->reflectProperty($this->soundcloud, 'factory');
        $reflectedFactory->setValue($this->soundcloud, $factory);

        $returnValue = $this->soundcloud->me();
        $this->assertInstanceOf($expected, $returnValue);
    }

    public function testUserCredentials()
    {
        $token = "12345-ABCD";
        $data = new \stdClass();
        $data->access_token = $token;
        $data->refresh_token = "54321-DCBA";
        $data->scope = "non-expiring";

        $response = $this->getResponseMock('bodyObject', function() use ($data) { return $data; });
        $request = $this->getRequestMock($response);
        $factory = $this->getFactoryMock($request, $response);
        $reflectedFactory = $this->reflectProperty($this->soundcloud, 'factory');
        $reflectedFactory->setValue($this->soundcloud, $factory);

        $this->soundcloud->userCredentials('User', 'Password');
        $resultToken = $this->soundcloud->auth()->token();
        $this->assertEquals($token, $resultToken);
    }

    public function testRefreshToken()
    {
        $token = "12345-ABCD";
        $data = new \stdClass();
        $data->access_token = $token;
        $data->refresh_token = "54321-DCBA";
        $data->scope = "non-expiring";

        $response = $this->getResponseMock('bodyObject', function() use ($data) { return $data; });
        $request = $this->getRequestMock($response);
        $factory = $this->getFactoryMock($request, $response);
        $reflectedFactory = $this->reflectProperty($this->soundcloud, 'factory');
        $reflectedFactory->setValue($this->soundcloud, $factory);

        $this->soundcloud->refreshAccessToken();
        $resultToken = $this->soundcloud->auth()->token();
        $this->assertEquals($token, $resultToken);
    }

    public function testCodeForToken()
    {
        $token = "12345-ABCD";
        $data = new \stdClass();
        $data->access_token = $token;
        $data->refresh_token = "54321-DCBA";
        $data->scope = "non-expiring";

        $response = $this->getResponseMock('bodyObject', function() use ($data) { return $data; });
        $request = $this->getRequestMock($response);
        $factory = $this->getFactoryMock($request, $response);
        $reflectedFactory = $this->reflectProperty($this->soundcloud, 'factory');
        $reflectedFactory->setValue($this->soundcloud, $factory);

        $this->soundcloud->codeForToken('code');
        $resultToken = $this->soundcloud->auth()->token();
        $this->assertEquals($token, $resultToken);
    }

    public function testFactory()
    {
        $returnValue = $this->soundcloud->factory();
        $this->assertInstanceOf('\Njasm\Soundcloud\Factory\LibraryFactory', $returnValue);
    }

    public function testResolve()
    {
        $expected = 'https://api.soundcloud.com/users/1492543?consumer_key=apigee';

        $data = include __DIR__ . '/Data/Serialized_Resolve.php';
        $response = $this->getResponseMock('bodyRaw', function() use ($data) { return $data; });
        $request = $this->getRequestMock($response);
        $factory = $this->getFactoryMock($request, $response);
        $reflectedFactory = $this->reflectProperty($this->soundcloud, 'factory');
        $reflectedFactory->setValue($this->soundcloud, $factory);

        $returnValue = $this->soundcloud->resolve('http:://api.soundcloud.com/user-name');
        $this->assertInstanceOf('\Njasm\Soundcloud\Resolve\Resolve', $returnValue);
        $this->assertEquals($expected, $returnValue->location());
    }

    public function testDownload()
    {
        $data = 'BIG_TRACK_DATA';
        $response = $this->getResponseMock('bodyRaw', function() use ($data) { return $data; });
        $request = $this->getRequestMock($response);
        $factory = $this->getFactoryMock($request, $response);
        $reflectedFactory = $this->reflectProperty($this->soundcloud, 'factory');
        $reflectedFactory->setValue($this->soundcloud, $factory);

        $returnValue = $this->soundcloud->download(123)->bodyRaw();
        $this->assertEquals("BIG_TRACK_DATA", $returnValue);
    }

    public function testUpload()
    {
        $data = 'SUCCESS_UPLOAD';
        $response = $this->getResponseMock('bodyRaw', function() use ($data) { return $data; });
        $request = $this->getRequestMock($response);
        $factory = $this->getFactoryMock($request, $response);
        $reflectedFactory = $this->reflectProperty($this->soundcloud, 'factory');
        $reflectedFactory->setValue($this->soundcloud, $factory);

        $returnValue = $this->soundcloud->upload(__DIR__ . '/../bootstrap.php')->bodyRaw();
        $this->assertEquals("SUCCESS_UPLOAD", $returnValue);
    }
}
