<?php

namespace Njasm\Soundcloud\Collection;

use Njasm\Soundcloud\Resource\AbstractResource;

class Collection implements \Iterator
{
    /** @var array */
    private $items = [];

    public function add(AbstractResource $r)
    {
        $this->items[$r->get('id')] = $r;
    }

    public function remove(AbstractResource $r)
    {
        $id = $r->get('id');
        if (isset($this->items[$id])) {
            unset($this->items[$id]);
        }
    }

    public function key()
    {
        return key($this->items);
    }

    public function valid()
    {
        return current($this->items) !== false;
    }

    public function current()
    {
        return current($this->items);
    }

    public function next()
    {
        next($this->items);
    }

    public function rewind()
    {
        reset($this->items);
    }

    public function count()
    {
        return count($this->items);
    }
}
