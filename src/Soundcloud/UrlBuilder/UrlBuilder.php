<?php

namespace Njasm\Soundcloud\UrlBuilder;


class UrlBuilder implements UrlBuilderInterface
{
    private $query;
    private $scheme = "https://";
    private $hostname = "soundcloud.com";
    private $subdomain;
    private $verb;
    private $path;

    public function __construct($path = null, array $params = array(), $verb = "get", $subdomain = "api")
    {
        $this->path = $path;
        $this->subdomain = $subdomain;
        $this->verb = $verb;
        $this->setQuery($params);
    }
    
    
    /**
     * @return string uri query.
     */
    public function getQuery()
    {
        return $this->query;
    }
    
    public function setQuery(array $params = array())
    {
        if (!empty($params)) {
            $this->query = http_build_query($params);
        } else {
            $this->query = null;
        }
    }
    
    public function getUrl()
    {
        $url = $this->scheme . $this->subdomain . "." . $this->hostname;
        $url .= ($this->path) ?: '';
        
        if (strtolower($this->verb) == "get") {            
            $url .= ($this->query) ? "?" . $this->query : '';
        }
        
        return $url;
    }
}