<?php

namespace Njasm\Soundcloud\Tests\Resource;

use Njasm\Soundcloud\Factory\ApiResponseFactory;
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
        $this->setSoundcloudMockObjects($data);

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
        $this->setSoundcloudMockObjects($data);

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
        $this->user->delete();
    }

    public function testUpdateException()
    {
        $this->setExpectedException('\Exception');
        $this->user->update();
    }

    public function testUpdateReturnNewObject()
    {
        $data = include __DIR__ . '/../Data/Serialized_User.php';
        $this->setSoundcloudMockObjects($data);

        $this->user->set('id', 1);
        $this->user->update(false);
        $this->assertEquals('1492543', $this->user->get('id'));

    }

    public function testUpdate()
    {
        $data = include __DIR__ . '/../Data/Serialized_User.php';
        $this->setSoundcloudMockObjects($data);

        $this->user->set('id', 1);
        $newUser = $this->user->update();
        $this->assertEquals(1, $this->user->get('id'));
        $this->assertEquals('1492543', $newUser->get('id'));
    }

    public function testGetTracks()
    {
        $data = include __DIR__ . '/../Data/Serialized_Collection_Track.php';
        $this->setSoundcloudMockObjects($data);

        $this->user->set('id', 1);
        $collection = $this->user->getTracks();
        $this->assertInstanceOf('\Njasm\Soundcloud\Collection\TrackCollection', $collection);
        $this->assertEquals(10, $collection->count());
    }

    public function testGetPlaylists()
    {
        // playlist is empty so, a Collection object must be returned instead of a PlaylistCollection
        $data = '{}';
        $this->setSoundcloudMockObjects($data);

        $this->user->set('id', 1);
        $collection = $this->user->getPlaylists();
        $this->assertInstanceOf('\Njasm\Soundcloud\Collection\Collection', $collection);
        $this->assertEquals(0, $collection->count());
    }

    public function testGetFollowings()
    {
        $data = include __DIR__ . '/../Data/Serialized_Collection_User.php';
        $this->setSoundcloudMockObjects($data);

        $this->user->set('id', 1);
        $collection = $this->user->getFollowings();

        $this->assertInstanceOf('\Njasm\Soundcloud\Collection\UserCollection', $collection);
        $this->assertEquals(2, $collection->count());
    }
}
