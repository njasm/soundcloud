<?php

namespace Njasm\Soundcloud\Resource;

use Njasm\Soundcloud\Exception\SoundcloudException;

/**
 * SoundCloud API wrapper in PHP
 *
 * @author      Nelson J Morais <njmorais@gmail.com>
 * @copyright   2014 Nelson J Morais <njmorais@gmail.com>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @link        http://github.com/njasm/soundcloud
 * @package     Njasm\Soundcloud
 * @version     1.1.0-BETA
 */

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
            break;
            default:
                throw new SoundcloudException("Resource of type: $name, not available!");              
        };
        
        return new self($name, $path, $params);
    }
}
