<?php

namespace Njasm\Soundcloud\Tests\Auth;

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
}
