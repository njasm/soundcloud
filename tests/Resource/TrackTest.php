<?php

namespace Njasm\Soundcloud\Tests\Resource;

use \Njasm\Soundcloud\Resource\Track;
use Njasm\Soundcloud\Soundcloud;

class TrackTest extends \PHPUnit_Framework_TestCase
{
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
        $data = include __DIR__ . '/../Factory/Serialized_User.php';

        $this->assertTrue($this->track->isNew());
        $this->track->unserialize($data);
        $this->assertFalse($this->track->isNew());
    }
}
