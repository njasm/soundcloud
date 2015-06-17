<?php

namespace Njasm\Soundcloud\Auth;

/**
 * SoundCloud API wrapper in PHP
 *
 * @author      Nelson J Morais <njmorais@gmail.com>
 * @copyright   2014 Nelson J Morais <njmorais@gmail.com>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @link        http://github.com/njasm/soundcloud
 * @package     Njasm\Soundcloud
 */

class Auth implements AuthInterface
{
    private $clientID;
    private $clientSecret;
    private $callback;
    
    private $accessToken;
    private $expires;
    private $scope;
    private $refreshToken;
    
    public function __construct($clientID = null, $clientSecret = null, $callback = null)
    {
        if ($this->isValidClientID($clientID) === false) {
            throw new \InvalidArgumentException("No ClientID Provided.");            
        }
        
        $this->clientID = $clientID;
        $this->clientSecret = $clientSecret;
        $this->callback = $callback;
    }
    
    /**
     * @param string $clientID
     * @return boolean
     */
    private function isValidClientID($clientID)
    {
        return is_string($clientID) === true && empty($clientID) === false;
    }
    
    public function clientID()
    {
        return $this->clientID;
    }
    
    public function clientSecret()
    {
        return $this->clientSecret;
    }
    
    public function urlCallback()
    {
        return $this->callback;
    }
    
    public function setToken($token)
    {
        $this->accessToken = $token;
    }
    
    public function token()
    {
        return $this->accessToken;
    }
    
    public function hasToken()
    {
        return empty($this->accessToken) === false ? true : false;
    }
    
    public function setScope($scope)
    {
        $this->scope = $scope;
    }
    
    public function scope()
    {
        return $this->scope;
    }
    
    public function setExpires($expires)
    {
        $this->expires = $expires;
    }
    
    public function expires()
    {
        return $this->expires;
    }
    
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }
    
    public function refreshToken()
    {
        return $this->refreshToken;
    }
    
    public function mergeParams(array $params = array(), $includeClientSecret = false)
    {
        if ($this->accessToken !== null) {
            return array_merge($params, array('oauth_token' => $this->accessToken));
        }
        
        if ($includeClientSecret === true) {
            $params = array_merge($params, array('client_secret' => $this->clientSecret));
        }
        
        return array_merge($params, array('client_id' => $this->clientID));              
    }
}
