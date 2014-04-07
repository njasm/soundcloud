<?php

namespace Njasm\Soundcloud\UrlBuilder;

use Njasm\Soundcloud\Resource\ResourceInterface;
use Njasm\Soundcloud\Auth\AuthInterface;

class UrlBuilder implements UrlBuilderInterface
{
    private $params = array();
    private $scheme;
    private $hostname;
    private $subdomain;
    private $resource;

    public function __construct(ResourceInterface $resource, $subdomain = "api", $hostname = "soundcloud.com", $scheme = "https://")
    {
        $this->resource = $resource;
        $this->scheme = $scheme;
        $this->subdomain = $subdomain;
        $this->hostname = $hostname;
        $this->setParams($this->resource->getParams());
    }
    
    public function getParams()
    {
        return $this->params;
    }
    
    public function setParams(array $params = array())
    {
        $this->params = $params;
    }
    
    public function getUrl()
    {
        $url = $this->scheme . $this->subdomain . "." . $this->hostname;
        $url .= $this->getCleanPath($this->resource->getPath());
        $verb = strtoupper($this->resource->getVerb());
        
        if ($verb == 'GET' && !empty($this->getParams())) {
            $url .= '?' . http_build_query($this->getParams());
        }

        return $url;
    }
    
    private function getCleanPath($path)
    {
        if (substr($path, strlen($path) - 1) == "/") {
            $path = substr($path, 0, strlen($path) - 1);
        }
        
        return $path;
    }
}