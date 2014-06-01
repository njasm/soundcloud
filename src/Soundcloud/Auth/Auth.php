<?php

namespace Njasm\Soundcloud\Auth;

use Njasm\Soundcloud\Auth\AuthInterface;

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
    private $authUrlCallback;
    
    private $accessToken;
    private $expires;
    private $scope;
    private $refreshToken;
    
    public function __construct($clientID = null, $clientSecret = null, $authUrlCallback = null)
    {
        if ($this->isValidClientID($clientID) === false) {
            throw new \InvalidArgumentException("No ClientID Provided.");            
        }
        
        $this->clientID = $clientID;
        $this->clientSecret = $clientSecret;
        $this->authUrlCallback = $authUrlCallback;
    }
    
    /**
     * @param string $clientID
     * @return boolean
     */
    private function isValidClientID($clientID)
    {
        return is_string($clientID) === true && empty($clientID) === false;
    }
    
    public function getClientID()
    {
        return $this->clientID;
    }
    
    public function getClientSecret()
    {
        return $this->clientSecret;
    }
    
    public function getAuthUrlCallback()
    {
        return $this->authUrlCallback;
    }
    
    public function setToken($token)
    {
        $this->accessToken = $token;
    }
    
    public function getToken()
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
    
    public function getScope()
    {
        return $this->scope;
    }
    
    public function setExpires($expires)
    {
        $this->expires = $expires;
    }
    
    public function getExpires()
    {
        return $this->expires;
    }
    
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }
    
    public function getRefreshToken()
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
