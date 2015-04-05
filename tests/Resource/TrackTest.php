<?php

namespace Njasm\Soundcloud\Tests\Resource;

use \Njasm\Soundcloud\Resource\Track;
use Njasm\Soundcloud\Soundcloud;

class TrackTest extends \PHPUnit_Framework_TestCase
{
    use \Njasm\Soundcloud\Tests\MocksTrait, \Njasm\Soundcloud\Tests\ReflectionsTrait;

    /** @var Track */
    protected $track;

    public function setUp()
    {
        $sc = new Soundcloud("ClientID", "ClientSecret");
        $this->track = new Track($sc); // empty/new track
    }

    public function testGetSet()
    {
        $null = $this->track->get("non-existent");
        $this->assertNull($null);

        $this->track->set('name', 'John Doe');
        $this->assertEquals('John Doe', $this->track->get('name'));
    }

    public function testIsNew()
    {
        $this->assertTrue($this->track->isNew());

        $this->track->set('id', 1);
        $this->assertFalse($this->track->isNew());
    }

    public function testUnserialize()
    {
        $data = include __DIR__ . '/../Data/Serialized_User.php';

        $this->assertTrue($this->track->isNew());
        $this->track->unserialize($data);
        $this->assertFalse($this->track->isNew());
    }

    public function testDownloadNewResource()
    {
        $this->setExpectedException('\LogicException');
        $this->track->download();
    }

    public function testDownload()
    {
        $data = 'BIG_TRACK_DATA';
        $response = $this->getResponseMock('bodyRaw', function() use ($data) { return $data; });
        $request = $this->getRequestMock($response);
        $factory = $this->getFactoryMock($request, $response);
        $reflectedFactory = $this->reflectProperty(Soundcloud::instance(), 'factory');
        $reflectedFactory->setValue(Soundcloud::instance(), $factory);

        $this->track->set('id', 1);
        $response = $this->track->download();
        $this->assertEquals("BIG_TRACK_DATA", $response->bodyRaw());
    }

    public function testSaveUpdateDelete()
    {
        $this->assertNull($this->track->save());
        $this->assertNull($this->track->delete());
        $this->assertNull($this->track->update());
    }
}
