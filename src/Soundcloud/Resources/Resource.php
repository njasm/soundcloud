<?php

namespace Njasm\Soundcloud\Resources;

use Njasm\Soundcloud\Resources;
use Njasm\Soundcloud\Exceptions\SoundcloudException;

class Resource implements ResourceInterface 
{
    private $path;
    private $params = array();
    private $verb;
    
    private function __construct($verb, $path = null, array $params = array())
    {
        $this->verb = $verb;
        $this->params = $params;        
        
        if (is_string($path) && substr($path, 0, 1) == "/") {
            $this->path = $path;
        } else {
            throw new SoundcloudException("Path cannot be other then a string type and should start with a '/' (Slash).");
        }
    }
    
    public function setParams(array $params = array())
    {
        if (!empty($params)) {
            $this->params = array_merge($this->params, $params);
        }
    }
    
    public function getParams()
    {
        return $this->params;
    }
    
    public function getPath()
    {
        return $this->path;
    }
    
    public function getVerb()
    {
        return $this->verb;
    }
    
    public static function __callStatic($name, $arguments)
    {
        switch ($name) {
            case 'get':
            case 'post':
            case 'put':
            case 'patch':
            case 'options':          
            case 'delete':
                $path = !empty($arguments[0]) ? $arguments[0] : null;
                $params = isset($arguments[1]) ? $arguments[1] : array();
                return new self($name, $path, $params);
            break;
            default:
                throw new SoundcloudException("Resource of type: $name, not available!");
                                
        };
    }
}



