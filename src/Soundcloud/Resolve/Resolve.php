<?php

namespace Njasm\Soundcloud\Resolve;

class Resolve {
    /** @var string */
    protected $statusCode;
    /** @var string */
    protected $statusString;
    /** @var string */
    protected $location;


    public function __construct($status, $location = '')
    {
        list($this->statusCode, $this->statusString) = explode(" - ", $status, 2);
        $this->location = $location;
    }

    public function found()
    {
        return $this->statusCode == 302;
    }

    public function statusCode()
    {
        return $this->statusCode;
    }

    public function statusString()
    {
        return $this->statusString;
    }

    public function location()
    {
        return $this->location;
    }

    public function __toString()
    {
        return $this->location;
    }
}