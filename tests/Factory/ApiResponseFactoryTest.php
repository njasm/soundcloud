<?php

namespace Njasm\Soundcloud\Tests\Factory;

use Njasm\Soundcloud\Factory\ApiResponseFactory;

class ApiResponseFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $this->setExpectedException('\Njasm\Soundcloud\Exception\SoundcloudResponseException');
        $errors = array('errors' => array());
        ApiResponseFactory::unserialize(json_encode($errors));
    }

    public function testGenericCollection()
    {
        $value = array();
        $this->assertInstanceOf(
            '\Njasm\Soundcloud\Collection\Collection',
            ApiResponseFactory::unserialize(json_encode($value))
        );
    }

    public function testNonExistentCollectionException()
    {
        $this->setExpectedException('\Exception');
        $value = array(array('kind' => 'non-existent'));
        ApiResponseFactory::unserialize(json_encode($value));
    }

    public function testResource()
    {
        $value = array('kind' => 'track');
        $this->assertInstanceOf(
            '\Njasm\Soundcloud\Resource\Track',
            ApiResponseFactory::resource($value)
        );

        $this->assertInstanceOf(
            '\Njasm\Soundcloud\Resource\Track',
            ApiResponseFactory::resource(json_encode($value))
        );
    }

    public function testResolve()
    {
        $data = "{\"status\": \"302 - Found\",\"location\": \"https://api.soundcloud.com/users/1492543?consumer_key=apigee\"}";
        $expected = '\Njasm\Soundcloud\Resolve\Resolve';
        $resolve = ApiResponseFactory::resolve($data);

        $this->assertInstanceOf($expected, $resolve);

        $resolve2 = ApiResponseFactory::unserialize($data);
        $this->assertInstanceOf($expected, $resolve2);
    }

    public function testCollectionWithItems()
    {
        $data = '[{"kind": "comment", "id": 1}, {"kind": "comment", "id": 2}]';
        $expectedCollection = '\Njasm\Soundcloud\Collection\CommentCollection';
        $returnValue = ApiResponseFactory::unserialize($data);
        $this->assertInstanceOf($expectedCollection, $returnValue);
        $this->assertCount(2, $returnValue);
    }

    public function testUnserializeResource()
    {
        $data = include __DIR__ . '/../Data/Serialized_User.php';
        $expected = '\Njasm\Soundcloud\Resource\User';
        $returnValue = ApiResponseFactory::unserialize($data);
        $this->assertInstanceOf($expected, $returnValue);
    }
}
