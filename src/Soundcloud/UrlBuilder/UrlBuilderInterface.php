<?php

namespace Njasm\Soundcloud\UrlBuilder;

interface UrlBuilderInterface 
{
    
    public function __construct($path = null, array $params = array(), $verb = "get", $subdomain = "api");    
    public function getQuery();
    
    /**
     * Returns fully qualified URL.
     * 
     * @return string fully qualified url to query a soundcloud api resource
     */
    public function getUrl();
    
    /**
     * Build http query.
     * 
     * @param array $params key value pair to build the query.
     * @return void
     */    
    public function setQuery(array $params = array());    
}