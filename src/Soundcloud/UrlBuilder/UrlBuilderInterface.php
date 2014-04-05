<?php

namespace Njasm\Soundcloud\UrlBuilder;

use Njasm\Soundcloud\Resource\ResourceInterface;
use Njasm\Soundcloud\Auth\AuthInterface;

interface UrlBuilderInterface 
{
    /**
     * Set http params to be used with this resource
     * 
     * @return void
     */
    public function setParams(array $params);
    
    /**
     * Get params of this resource
     * 
     * @return array (key => value) pairs
     */
    public function getParams();
    
    /**
     * Get full URL for the request
     * 
     * @return string the fully qualified url
     */
    public function getUrl();    
}