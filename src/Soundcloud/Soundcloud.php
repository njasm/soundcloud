<?php

namespace Njasm\Soundcloud;

use Njasm\Soundcloud\Resources\Resource;
use Njasm\Soundcloud\Exceptions\SoundcloudException;

/**
 * SoundCloud API wrapper in PHP
 *
 * @author      Nelson J Morais <njmorais@gmail.com>
 * @copyright   2014 Nelson J Morais <njmorais@gmail.com>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @link        http://github.com/njasm/soundcloud
 * @category    Services
 * @package     Soundcloud
 * @version     2.0.0-ALPHA
 * @todo        Error Handling
 */
Class Soundcloud {

    /**
     * Soundcloud API Client ID
     * @var string
     * @access private
     */
    private $clientID;

    /**
     * Soundcloud API Client Secret
     * @var string
     * @access private
     */
    private $clientSecret;

    /**
     * Soundcloud API Authorization Callback Uri
     * @var string
     * @access private
     */
    private $authCallbackUri;

    /**
     * Soundcloud api Oauth2 Token
     * @var string
     * @access private
     */
    private $token;
    
    /**
     * Soundcloud api Oauth2 End User Point
     * @var string
     * @access private
     */
    private $endUserAuthorization;
    
    private $resource;
    private $request;
    private $response;
    private $urlBuilder;
    
    public function __construct($clientID = null, $clientSecret = null, $authCallbackUri = null)
    {
        if (is_string($clientID)) {
            $this->clientID = $clientID;
        } else {
            throw new \InvalidArgumentException("Api ClientID missing.");
        }
        
        if (is_string($clientSecret)) {
            $this->clientSecret = $clientSecret;
        } else {
            throw new \InvalidArgumentException("Api ClientSecret missing.");
        }
        
        $this->authCallbackUri = $authCallbackUri;  
    }
    
    public function getAuthorizeUrl()
    {
        if (empty($this->token)) {
            
        }
    }
    
    /**
     * Sets up a GET Resource.
     * 
     * @param string $path
     * @param array $params
     * @return \Njasm\Soundcloud\Soundcloud Soundcloud
     */
    public function get($path = null, array $params = array())
    {
        $this->resource = Resource::get($path, $params);
        return $this;
    }

    /**
     * Sets up a PUT Resource.
     * 
     * @param string $path
     * @param array $params
     * @return \Njasm\Soundcloud\Soundcloud Soundcloud
     */    
    public function put($path = null, array $params = array())
    {
        $this->resource = Resource::put($path, $params);
        return $this;
    }
    
    /**
     * Sets up a POST Resource.
     * 
     * @param string $path
     * @param array $params
     * @return \Njasm\Soundcloud\Soundcloud Soundcloud
     */    
    public function post($path = null, array $params = array())
    {
        $this->resource = Resource::post($path, $params);
        return $this;
    }
    
    /**
     * Sets up a DELETE Resource.
     * 
     * @param string $path
     * @param array $params
     * @return \Njasm\Soundcloud\Soundcloud Soundcloud
     */
    public function delete($path = null, array $params = array())
    {
        $this->resource = Resource::delete($path, $params);   
        return $this;
    }   
    
    /**
     * Sets resource params.
     * 
     * @param array $params
     * @return \Njasm\Soundcloud\Soundcloud Soundcloud
     * @throws SoundcloudException
     */
    public function setParams(array $params = array())
    {
        if ($this->resource instanceof Resources\ResourceInterface) {
            $this->resource->setParams($params);
        } else {
            throw new SoundcloudException("No Resource found. you must call a http verb method before " . __METHOD__);
        }
        
        return $this;
    }
    
    /**
     * 
     * @param array $options cURL Options to merge with default options.
     */
    public function request(array $options = array())
    {
        
    }
}