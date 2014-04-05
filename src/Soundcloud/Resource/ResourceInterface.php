<?php
namespace Njasm\Soundcloud\Resource;

interface ResourceInterface 
{
    /**
     * Get resource path
     * 
     * @return string 
     */
    public function getPath();
    
    /**
     * Set resource params
     * 
     * @param array $params Associative array (key => value) pairs
     * @return void
     */
    public function setParams(array $params = array());
    
    /**
     * Get params array (key => value) pairs
     * 
     * @return array
     */
    public function getParams();
    
    /**
     * Get resource HTTP Method/Verb
     * 
     * @return string 
     */
    public function getVerb();
}

