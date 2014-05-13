<?php

namespace Njasm\Soundcloud\UrlBuilder;

use Njasm\Soundcloud\Resource\ResourceInterface;

/**
 * SoundCloud API wrapper in PHP
 *
 * @author      Nelson J Morais <njmorais@gmail.com>
 * @copyright   2014 Nelson J Morais <njmorais@gmail.com>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @link        http://github.com/njasm/soundcloud
 * @package     Njasm\Soundcloud
 */

class UrlBuilder implements UrlBuilderInterface
{
    private $scheme;
    private $hostname;
    private $subdomain;
    private $resource;

    public function __construct(
        ResourceInterface $resource,
        $subdomain = "api",
        $hostname = "soundcloud.com",
        $scheme = "https://"
    ) {
        $this->resource = $resource;
        $this->scheme = $scheme;
        $this->subdomain = $subdomain;
        $this->hostname = $hostname;
    }
    
    public function getUrl()
    {
        $url = $this->scheme . $this->subdomain . "." . $this->hostname;
        $url .= $this->getCleanPath($this->resource->getPath());
        $verb = strtoupper($this->resource->getVerb());
        $params = $this->resource->getParams();
        
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
