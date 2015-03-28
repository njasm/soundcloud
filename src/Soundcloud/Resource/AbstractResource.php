<?php

namespace Njasm\Soundcloud\Resource;

use Njasm\Soundcloud\Soundcloud;

abstract class AbstractResource implements \Serializable
{
    /** @var Soundcloud */
    protected $sc;

    /** @var array Soundcloud Resource Properties */
    protected $properties = [];

    /** @var array should be overwritten by sub class */
    protected $writableProperties = [];

    final public function __construct(Soundcloud $sc, array $data = [])
    {
        $this->sc = $sc;
        empty($data) or $this->unserialize($data);
    }

    /**
     * Check if Resource is new.
     *
     * @return bool
     */
    public function isNew()
    {
        return is_null($this->get('id'));
    }

    /**
     * Returns Resource id.
     *
     * @return null|int
     */
    public function id()
    {
        return $this->get('id');
    }

    public function get($property)
    {
        if (!isset($this->properties[$property])) {
            //throw new \Exception("Property $property non-existent.");
            return null;
        }

        return $this->properties[$property];
    }

    public function set($property, $value)
    {
        return $this->properties[$property] = $value;
    }

    /**
     * @throws \Exception
     * @return array
     */
    public function serialize()
    {
        if (empty($this->writableProperties)) {
            throw new \Exception("Resource have no writable properties");
        }

        $data = [];
        foreach($this->writableProperties as $property) {
            $resultKey = $this->resource . '[' . strtolower($property) . ']';
            if (!isset($this->properties[$property])) {
                $data[$resultKey] = null;
            }

            $data[$resultKey] = $this->properties[$property];
        }

        return $data;
    }

    /**
     * @param string $serialized
     * @return void
     */
    public function unserialize($serialized)
    {
        if (is_array($serialized)) {
            $this->properties = $serialized;
            return;
        }

        $data = json_decode($serialized, true);
        $this->properties = $data;
    }

    abstract public function save();
    abstract public function update();
    abstract public function delete();
}
