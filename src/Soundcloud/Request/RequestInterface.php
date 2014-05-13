<?php

namespace Njasm\Soundcloud\Request;

interface RequestInterface
{
    /**
     * Set curl options.
     * 
     * @param array $options (key => value) pairs
     * @return void 
     */
    public function setOptions(array $options);
    
    /**
     * Get curl options
     * 
     * @return array (key => value) pairs
     */
    public function getOptions();
    
    /**
     * Execute a curl request
     * 
     * @return Response The Response object
     */
    public function exec();
    
    /**
     * Sets the request to accept XML response
     * 
     * @return void
     */
    public function asXml();
    
    /**
     * Sets the request to accept JSON response
     * 
     * @return void
     */
    public function asJson();
}
