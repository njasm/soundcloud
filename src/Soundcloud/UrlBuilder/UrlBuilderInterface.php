<?php

namespace Njasm\Soundcloud\UrlBuilder;

interface UrlBuilderInterface
{
    /**
     * Get full URL for the request
     * 
     * @return string the fully qualified url
     */
    public function getUrl();
}
