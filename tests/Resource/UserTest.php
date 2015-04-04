<?php

namespace Njasm\Soundcloud\Tests\Resource;

use Njasm\Soundcloud\Resource\User;
use Njasm\Soundcloud\Soundcloud;

class UserTest extends \PHPUnit_Framework_TestCase
{
    use \Njasm\Soundcloud\Tests\MocksTrait, \Njasm\Soundcloud\Tests\ReflectionsTrait;

    /** @var User */
    public $user;
    /** @var Soundcloud */
    public $sc;

    public function setUp()
    {
        $this->sc = new Soundcloud("ClientID", "ClientSecret");
        $this->user = new User($this->sc); // empty/new user
    }

    public function testRefresh()
    {
        $data = include __DIR__ . '/../Data/Serialized_User.php';
        $response = $this->getResponseMock('bodyRaw', function() use ($data) { return $data; });
        $request = $this->getRequestMock($response);
        $factory = $this->getFactoryMock($request, $response);
        $reflectedFactory = $this->reflectProperty($this->sc, 'factory');
        $reflectedFactory->setValue($this->sc, $factory);

        $soundcloud = $this->getSoundcloudMock();
        $reflectedSoundcloud = $this->reflectProperty($this->sc, 'self');
        $reflectedSoundcloud->setValue($this->sc, $soundcloud);

        $this->user->set('id', 1);
        $this->user->refresh();
        $this->assertEquals('1492543', $this->user->get('id'));
    }

    public function testRefreshException()
    {
        $this->setExpectedException('\LogicException');
        $this->user->refresh();
    }

    public function testRefreshReturnNewObject()
    {
        $data = include __DIR__ . '/../Data/Serialized_User.php';
        $response = $this->getResponseMock('bodyRaw', function() use ($data) { return $data; });
        $request = $this->getRequestMock($response);
        $factory = $this->getFactoryMock($request, $response);
        $reflectedFactory = $this->reflectProperty($this->sc, 'factory');
        $reflectedFactory->setValue($this->sc, $factory);

        $soundcloud = $this->getSoundcloudMock();
        $reflectedSoundcloud = $this->reflectProperty($this->sc, 'self');
        $reflectedSoundcloud->setValue($this->sc, $soundcloud);

        $this->user->set('id', 1);
        $newUser = $this->user->refresh(true);
        $this->assertEquals('1492543', $newUser->get('id'));
    }

    public function testSave()
    {
        $this->setExpectedException('\Exception');
        $this->user->save();
    }

    public function testDelete()
    {
        $this->setExpectedException('\Exception');
        $this->user->update();
    }
}
