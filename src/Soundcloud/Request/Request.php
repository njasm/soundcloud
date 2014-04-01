<?php

namespace Njasm\Soundcloud\Request;

use Njasm\Soundcloud\Resource\ResourceInterface;
use Njasm\Soundcloud\UrlBuilder\UrlBuilderInterface;
use Njasm\Soundcloud\Auth\AuthInterface;

class Request implements RequestInterface 
{
    private $resource;
    private $auth;
    private $urlBuilder;
    private $response;
        
    private $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
    );

    private $responseFormat = 'application/json';
    
    public function __construct(\ResourceInterface $resource, array $options = array(), \UrlBuilderInterface $urlBuilder)
    {
        $this->resource = $resource;
        $this->urlBuilder = $urlBuilder;
        
        if (!empty($options)) {
            $this->setOptions($options);
        }
    }
    
    public function setOptions(array $options = array())
    {
        !empty($params) ? $this->options + $options : '';
        
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
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: ' . $this->responseFormat));
        curl_setopt_array($ch, $this->options);
    }    
}
