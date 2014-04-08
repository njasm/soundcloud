<?php

namespace Njasm\Soundcloud\UrlBuilder;

use Njasm\Soundcloud\Resource\ResourceInterface;
use Njasm\Soundcloud\Auth\AuthInterface;

class UrlBuilder implements UrlBuilderInterface
{
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
        return $this->resource->getParams();
    }
    
    public function setParams(array $params = array())
    {
        $this->resource->setParams($params);
    }
    
    public function getUrl()
    {
        $url = $this->scheme . $this->subdomain . "." . $this->hostname;
        $url .= $this->getCleanPath($this->resource->getPath());
        $verb = strtoupper($this->resource->getVerb());
        $params = $this->getParams();
        
        if ($verb == 'GET' && !empty($params)) {
            $url .= '?' . http_build_query($params);
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