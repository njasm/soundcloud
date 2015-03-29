<?php

namespace Njasm\Soundcloud\Tests\Collection;

use Njasm\Soundcloud\Collection\Collection;
use Njasm\Soundcloud\Resource\Track;
use Njasm\Soundcloud\Soundcloud;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    protected $resource;
    /** @var Collection */
    protected $collection;
    protected $sc;

    public function setUp()
    {
        $this->sc = new Soundcloud('ClientID', 'ClientSecret');
        $track = new Track($this->sc);
        $track->set('id', 1);
        $this->resource = $track;
        $this->collection = new Collection();
    }

    public function testCount()
    {
        $this->assertTrue(0 == $this->collection->count());
    }

    public function testAddItem()
    {
        $this->testCount();
        $this->collection->add($this->resource);

        $this->assertTrue(1 == $this->collection->count());
    }

    public function testAddAndRemove()
    {
        $this->testCount();
        $this->testAddItem();
        $track = new Track($this->sc);
        $track->set('id', '12'); // change resource id.
        $this->collection->add($track);

        $this->assertTrue(2 == $this->collection->count());

        $this->collection->remove($track);


        $this->assertTrue(1 == $this->collection->count());
    }

    public function testKeyValidAndCurrent()
    {
        $this->collection->add($this->resource);
        $this->assertTrue($this->collection->valid());
        $this->assertTrue($this->resource->get('id') == $this->collection->key());
        $this->assertEquals($this->resource, $this->collection->current());
    }

    public function testNext()
    {
        $this->collection->add($this->resource);
        $this->assertFalse($this->collection->next());
    }
}
