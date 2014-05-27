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
        $this->setClientID($clientID);
        $this->clientSecret = $clientSecret;
        $this->authUrlCallback = $authUrlCallback;
    }
    
    public function setClientID($clientID)
    {
        if ($this->isValidClientID($clientID) === false) {
            throw new \InvalidArgumentException("No ClientID Provided.");
        }
        
        $this->clientID = $clientID;
    }
    
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
        return empty($this->clientSecret) === false ? $this->clientSecret : null;
    }
    
    public function getAuthUrlCallback()
    {
        return empty($this->authUrlCallback) === false ? $this->authUrlCallback : null;
    }
    
    public function setToken($token)
    {
        $this->accessToken = $token;
    }
    
    public function getToken()
    {
        return empty($this->accessToken) === false ? $this->accessToken : null;
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
        return isset($this->scope) === true ? $this->scope : null;
    }
    
    public function setExpires($expires)
    {
        $this->expires = $expires;
    }
    
    public function getExpires()
    {
        return isset($this->expires) === true ? $this->expires : null;
    }
    
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }
    
    public function getRefreshToken()
    {
        return isset($this->refreshToken) === true ? $this->refreshToken : null;
    }
}
