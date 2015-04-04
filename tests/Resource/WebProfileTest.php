<?php

namespace Njasm\Soundcloud\Tests\Resource;

use \Njasm\Soundcloud\Soundcloud;
use \Njasm\Soundcloud\Resource\WebProfile;

class WebProfileTest extends \PHPUnit_Framework_TestCase
{
    use \Njasm\Soundcloud\Tests\MocksTrait, \Njasm\Soundcloud\Tests\ReflectionsTrait;

    public $sc;
    public $profile;

    public function setUp()
    {
        $this->sc = new Soundcloud("ClientID", "ClientSecret");
        $this->profile = new WebProfile($this->sc);
    }

    public function testRefresh()
    {
        $data = include __DIR__ . '/../Data/Serialized_WebProfile.php';
        $this->setSoundcloudMockObjects($data);

        $this->profile->set('id', 1);
        $this->profile->refresh();
        $this->assertEquals('39407071', $this->profile->get('id'));
    }

    public function testRefreshReturnNewObject()
    {
        $data = include __DIR__ . '/../Data/Serialized_WebProfile.php';
        $this->setSoundcloudMockObjects($data);

        $this->profile->set('id', 1);
        $newWebProfile = $this->profile->refresh(true);
        $this->assertEquals('39407071', $newWebProfile->get('id'));
    }

    public function testSave()
    {
        $data = include __DIR__ . '/../Data/Serialized_WebProfile.php';
        $this->setSoundcloudMockObjects($data);

        $this->profile->save();
        $this->assertEquals('39407071', $this->profile->get('id'));
    }

    public function testExistentSave()
    {
        $this->profile->set('id', 1);
        $this->setExpectedException('\LogicException');
        $this->profile->save();
    }

    public function testUpdateException()
    {
        $this->setExpectedException('\Exception');
        $this->profile->update();
    }

    public function testUpdateReturnNewObject()
    {
        $data = include __DIR__ . '/../Data/Serialized_WebProfile.php';
        $this->setSoundcloudMockObjects($data);

        $this->profile->set('id', 1);
        $this->profile->update();
        $this->assertEquals('39407071', $this->profile->get('id'));
    }

    public function testUpdate()
    {
        $data = include __DIR__ . '/../Data/Serialized_WebProfile.php';
        $this->setSoundcloudMockObjects($data);

        $this->profile->set('id', 1);
        $newWebProfile = $this->profile->update(false);
        $this->assertEquals(1, $this->profile->get('id'));
        $this->assertEquals('39407071', $newWebProfile->get('id'));
    }
}
