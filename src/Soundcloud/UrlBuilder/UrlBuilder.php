<?php

namespace Njasm\Soundcloud\UrlBuilder;

use Njasm\Soundcloud\Resource\ResourceInterface;
use Njasm\Soundcloud\Auth\AuthInterface;

class UrlBuilder implements UrlBuilderInterface
{
    private $query;
    private $scheme;
    private $hostname;
    private $subdomain;
    private $auth;
    private $resource;

    public function __construct(ResourceInterface $resource, AuthInterface $auth, $subdomain = "api", $hostname = "soundcloud.com", $scheme = "https://")
    {
        $this->resource = $resource;
        $this->auth = $auth;
        $this->scheme = $scheme;
        $this->subdomain = $subdomain;
        $this->hostname = $hostname;
        
        $this->setQuery($this->resource->getParams());
    }
    
    
    public function getQuery()
    {
        return $this->query;
    }
    
    public function setQuery(array $params = array())
    {
        $params = $this->mergeAuthParams($params);
        $this->query = http_build_query($params);
    }
    
    public function getUrl()
    {
        $url = $this->scheme . $this->subdomain . "." . $this->hostname;
        $path = $this->resource->getPath();
        
        if (!empty($path)) {
            $url .= $path;
        }
        if (strtolower($this->resource->getVerb()) == "get") {            
            $url .= ($this->query) ? "?" . $this->query : '';
        }
        
        return $url;
    }
    
    public function mergeAuthParams(array $params = array()) 
    {
        $return = array_merge(
            array(
                'client_id' => $this->auth->getClientID()
            ),
            $params
        );
        
        return $return;
    }
    
    public function getPath()
    {
        $path = $this->resource->getPath();
        if (substr($path, strlen($path) - 1) == "/") {
            $path = substr($path, 0, strlen($path) - 1);
        } else {
            $path .= "&client_id=" . $this->auth->getClientID();
        }
        
        return $path;
    }
}