<?php

namespace Njasm\Soundcloud\UrlBuilder;

use Njasm\Soundcloud\Resource\ResourceInterface;
use Njasm\Soundcloud\Auth\AuthInterface;

interface UrlBuilderInterface 
{
    /**
     * Get full URL for the request
     * 
     * @return string the fully qualified url
     */
    public function getUrl();    
}