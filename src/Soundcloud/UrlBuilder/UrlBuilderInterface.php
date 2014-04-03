<?php

namespace Njasm\Soundcloud\UrlBuilder;

interface UrlBuilderInterface 
{
    public function __construct(ResourceInterface $resource, 
                                AuthInterface $auth,
                                $subdomain = "api", 
                                $hostname = "soundcloud.com", 
                                $scheme = "https://"
                                );
    public function setQuery(array $params = array());
    public function getQuery();
    public function getUrl();    
}