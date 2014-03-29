<?php

namespace Njasm\Soundcloud\Resources;

use Njasm\Soundcloud\Resources;
use Njasm\Soundcloud\Exceptions\SoundcloudException;

class Resource implements ResourceInterface 
{
    private $path;
    private $params = array();
    private $verb;
    
    private function __construct($verb, $path, array $params = array())
    {
        $this->verb = $verb;
        $this->params = $params;        
        
        if (is_string($path) && substr($pat, 0, 1) == "/") {
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
                return new self($name, $arguments[0], $arguments[1]);
            break;
            case 'post':
                return new self($name, $arguments[0], $arguments[1]);
            break;
            case 'put':
                return new self($name, $arguments[0], $arguments[1]);
            break;
            case 'patch':
                return new self($name, $arguments[0], $arguments[1]);
            break;
            case 'options':
                return new self($name, $arguments[0], $arguments[1]);
            break;           
            case 'delete':
                return new self($name, $arguments[0], $arguments[1]);
            break;
            default:
                throw new SoundcloudException("Resource of type: $name, not available!");
                                
        };
    }
}



