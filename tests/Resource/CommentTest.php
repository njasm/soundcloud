<?php

namespace Njasm\Soundcloud\Tests\Resource;

use \Njasm\Soundcloud\Resource\Comment;
use \Njasm\Soundcloud\Soundcloud;

class CommentTest extends \PHPUnit_Framework_TestCase
{
    public function testSerialize()
    {
        $sc = new Soundcloud("ClientID", "ClientSecret");
        $commentUnserialized = ['body' => 'Test Comment', 'track_id' => 12345, 'user_id' => 6789, 'timestamp' => 123456789];
        $commentSerialized = ['comment[body]' => 'Test Comment', 'comment[track_id]' => 12345, 'comment[user_id]' => 6789, 'comment[timestamp]' => 123456789];
        $resource = new Comment($sc, $commentUnserialized);
        $returnValue = $resource->serialize();
        $this->assertEquals($commentSerialized, $returnValue);
    }
}
