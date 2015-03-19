<?php

namespace Njasm\Soundcloud\Resource;

use Njasm\Soundcloud;

abstract class AbstractResource
{
    /** @var Soundcloud */
    private $sc;

    /** @var array Soundcloud Resource Properties */
    private $properties = [];

    final public function __construct()
    {
        $this->sc = SoundcloudService::instance();
    }

    public function get($property)
    {
        return $this->properties[$property];
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->{$property} = $value;
        }

        if (array_key_exists($property, $this->properties)) {
            $this->properties[$property] = $value;
        }

        throw new \Exception("Property $property non-existent.");
    }
}
