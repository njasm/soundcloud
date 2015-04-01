<?php

namespace Njasm\Soundcloud\Tests\Auth;

use Njasm\Soundcloud\Factory\AbstractFactory;

class AbstractFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $this->setExpectedException('\Njasm\Soundcloud\Exception\SoundcloudResponseException');
        $errors = array('errors' => array());
        AbstractFactory::unserialize(json_encode($errors));
    }

    public function testGenericCollection()
    {
        $value = array();
        $this->assertInstanceOf(
            '\Njasm\Soundcloud\Collection\Collection',
            AbstractFactory::unserialize(json_encode($value))
        );
    }

    public function testNonExistentCollectionException()
    {
        $this->setExpectedException('\Exception');
        $value = array(array('kind' => 'non-existent'));
        AbstractFactory::unserialize(json_encode($value));
    }

    public function testResource()
    {
        $value = array('kind' => 'track');
        $this->assertInstanceOf(
            '\Njasm\Soundcloud\Resource\Track',
            AbstractFactory::resource($value)
        );

        $this->assertInstanceOf(
            '\Njasm\Soundcloud\Resource\Track',
            AbstractFactory::resource(json_encode($value))
        );
    }
}
