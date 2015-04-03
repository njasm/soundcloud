<?php

namespace Njasm\Soundcloud\Tests\Resource;

use \Njasm\Soundcloud\Resource\Comment;
use \Njasm\Soundcloud\Soundcloud;

class CommentTest extends \PHPUnit_Framework_TestCase
{
    public $sc;
    public $comment;
    public $commentSerialized;
    public $commentUnserialized;

    public function setUp()
    {
        $this->sc = new Soundcloud("ClientID", "ClientSecret");
        $this->commentUnserialized = ['body' => 'Test Comment', 'track_id' => 12345, 'user_id' => 6789, 'timestamp' => 123456789];
        $this->commentSerialized = ['comment[body]' => 'Test Comment', 'comment[track_id]' => 12345, 'comment[user_id]' => 6789, 'comment[timestamp]' => 123456789];
        $this->comment = new Comment($this->sc, $this->commentUnserialized);
    }

    public function testSerialize()
    {
        $returnValue = $this->comment->serialize();
        $this->assertEquals($this->commentSerialized, $returnValue);
    }

    public function testSerializeWithNull()
    {
        $commentUnserialized = ['body' => 'Test Comment', 'track_id' => 12345, 'user_id' => 6789];
        $commentSerialized = ['comment[body]' => 'Test Comment', 'comment[track_id]' => 12345, 'comment[user_id]' => 6789, 'comment[timestamp]' => null];
        $comment = new Comment($this->sc, $commentUnserialized);
        $returnValue = $comment->serialize();
        $this->assertEquals($returnValue, $commentSerialized);
    }

    public function testSerializeException()
    {
        $property = new \ReflectionProperty($this->comment, "writableProperties");
        $property->setAccessible(true);
        $property->setValue($this->comment, []);

        $this->setExpectedException('\Exception');
        $this->comment->serialize();
    }

    public function testIsNew()
    {
        $this->assertTrue($this->comment->isNew());
        $this->comment->set("id", 1);
        $this->assertFalse($this->comment->isNew());
        $this->assertEquals(1, $this->comment->id());
    }
}
