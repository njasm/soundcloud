<?php
/**
 * Created by PhpStorm.
 * User: njasm
 * Date: 19/03/15
 * Time: 21:51
 */

namespace Njasm\Soundcloud\Collection;

use Njasm\Soundcloud\Resource\AbstractResource;

class Collection implements \Iterator
{
    /** @var array */
    private $items = [];

    public function __construct()
    {

    }

    public function add(AbstractResource $r)
    {

    }

    public function remove(AbstractResource $r)
    {

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
}
