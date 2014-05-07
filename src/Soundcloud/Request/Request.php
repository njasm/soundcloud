<?php

namespace Njasm\Soundcloud\Request;

use Njasm\Soundcloud\Resource\ResourceInterface;
use Njasm\Soundcloud\UrlBuilder\UrlBuilderInterface;
use Njasm\Soundcloud\Auth\AuthInterface;
use Njasm\Soundcloud\Container\ContainerInterface;

/**
 * SoundCloud API wrapper in PHP
 *
 * @author      Nelson J Morais <njmorais@gmail.com>
 * @copyright   2014 Nelson J Morais <njmorais@gmail.com>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @link        http://github.com/njasm/soundcloud
 * @package     Njasm\Soundcloud
 */

class Request implements RequestInterface 
{
    private $resource;
    private $urlBuilder;
    private $container;

    private $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HEADER => true
    );

    private $responseFormat = 'application/json';
    
    public function __construct(ResourceInterface $resource, UrlBuilderInterface $urlBuilder, ContainerInterface $container)
    {
        $this->resource = $resource;
        $this->urlBuilder = $urlBuilder;
        $this->container = $container;
    }
    
    public function setOptions(array $options)
    {
        $this->options = $options + $this->options;
        
        return $this;
    }
    
    public function getOptions()
    {
        return $this->options;
    }
    
    public function asXml()
    {
        $this->responseFormat = 'application/xml';
        return $this;
    }
    
    public function asJson() 
    {
       $this->responseFormat = 'application/json'; 
       return $this;
    }
    
    public function exec()
    {
        $verb = strtoupper($this->resource->getVerb());
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: ' . $this->responseFormat));
        curl_setopt_array($ch, $this->options);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $verb);
        curl_setopt($ch, CURLOPT_URL, $this->urlBuilder->getUrl());
        
        if ($verb != 'GET') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->resource->getParams());
        }

        //DEBUG ONLY
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        $errno = curl_errno($ch);
        $errorString = curl_error($ch);
        curl_close($ch);

        return $this->container->make('ResponseInterface', array($response, $info, $errno, $errorString));
    }    
}
