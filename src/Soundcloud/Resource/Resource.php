<?php

namespace Njasm\Soundcloud\Resource;

/**
 * SoundCloud API wrapper in PHP
 *
 * @author      Nelson J Morais <njmorais@gmail.com>
 * @copyright   2014 Nelson J Morais <njmorais@gmail.com>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @link        http://github.com/njasm/soundcloud
 * @package     Njasm\Soundcloud
 */

class Resource implements ResourceInterface
{
    private $path;
    private $params = array();
    private $verb;
    private $availableVerbs = array('get', 'post', 'put', 'delete', 'patch', 'options');
    
    public function __construct($verb = null, $path = null, array $params = array())
    {
        $this->isValidVerb($verb);
        $this->verb = $verb;
        $this->params = $params;
        
        if ($this->isValidPath($path) === false) {
            throw new \RuntimeException(
                "Path cannot be other then a string type and should start with a '/' (Slash)."
            );
        }
        
        $this->path = $path;
    }
    
    public function setParams(array $params = array())
    {
        $this->params = array_merge($this->params, $params);
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
    
    private function isValidVerb($verb)
    {
        if (in_array(strtolower($verb), $this->availableVerbs) === false) {
            throw new \OutOfBoundsException("Resource of type: $verb, not available!");
        }
    }
    
    private function isValidPath($path)
    {
        return is_string($path) === true && substr($path, 0, 1) === "/";
    }
}
