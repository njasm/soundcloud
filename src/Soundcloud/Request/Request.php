<?php

namespace Njasm\Soundcloud;

use Njasm\Soundcloud\Resources;

class Request implements RequestInterface 
{
    private $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
    );

    private $resource;
    private $sc;
    private $responseFormat = 'application/json';
    
    public function __construct(\ResourceInterface $resource, array $options = array(), Soundcloud $sc)
    {
        $this->resource = $resource;
        $this->sc = $sc;
        
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
        
        curl_setopt($ch, $option, $ch);
    }    
}
