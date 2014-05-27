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
 */

class Resource implements ResourceInterface
{
    private $path;
    private $params = array();
    private $verb;
    private $availableVerbs = array('get', 'post', 'put', 'delete', 'patch', 'options');
    
    public function __construct($verb = null, $path = null, array $params = array())
    {
        $this->validate($verb);
        $this->verb = $verb;
        $this->params = $params;
        
        if (is_string($path) && substr($path, 0, 1) === "/") {
            $this->path = $path;
        } else {
            throw new SoundcloudException(
                "Path cannot be other then a string type and should start with a '/' (Slash)."
            );
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
    
    private function validate($verb)
    {
        if (in_array(strtolower($verb), $this->availableVerbs) === false) {
            throw new SoundcloudException("Resource of type: $verb, not available!");
        }
    }
}
