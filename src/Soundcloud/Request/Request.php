<?php

namespace Njasm\Soundcloud\Request;

use Njasm\Soundcloud\Resource\ResourceInterface;
use Njasm\Soundcloud\UrlBuilder\UrlBuilderInterface;
use Njasm\Soundcloud\Factory\FactoryInterface;

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
    private $factory;

    private $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 90,
        CURLOPT_HEADER => true
    );

    private $responseFormat = 'application/json';
    
    public function __construct(ResourceInterface $resource, UrlBuilderInterface $urlBuilder, FactoryInterface $factory)
    {
        $this->resource = $resource;
        $this->urlBuilder = $urlBuilder;
        $this->factory = $factory;
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
        
        $curlHandler = curl_init();
        curl_setopt($curlHandler, CURLOPT_HTTPHEADER, array('Accept: ' . $this->responseFormat));
        curl_setopt_array($curlHandler, $this->options);
        curl_setopt($curlHandler, CURLOPT_CUSTOMREQUEST, $verb);
        curl_setopt($curlHandler, CURLOPT_URL, $this->urlBuilder->getUrl());
        
        if ($verb !== 'GET') {
            curl_setopt($curlHandler, CURLOPT_POSTFIELDS, $this->resource->getParams());
        }
        
        $response = curl_exec($curlHandler);
        $info = curl_getinfo($curlHandler);
        $errno = curl_errno($curlHandler);
        $errorString = curl_error($curlHandler);
        curl_close($curlHandler);

        return $this->factory->make('ResponseInterface', array($response, $info, $errno, $errorString));
    }
}
