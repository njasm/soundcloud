<?php

namespace Njasm\Soundcloud\Resource;

use Njasm\Soundcloud;

abstract class AbstractResource
{
    /** @var Soundcloud */
    private $sc;


    final public function __construct(Soundcloud $sc)
    {
        $this->sc = $sc;
    }

    protected function save()
    {

    }

    protected function update()
    {

    }

    protected function delete()
    {

    }
}
