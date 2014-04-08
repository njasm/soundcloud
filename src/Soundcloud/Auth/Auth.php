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
 * @version     1.1.0-BETA
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
        $this->setClientSecret($clientSecret);
        $this->setAuthUrlCallback($authUrlCallback);
    }
    
    public function setClientID($clientID)
    {
        $clientID = trim($clientID);
        if (is_string($clientID) && !empty($clientID)) {
            $this->clientID = $clientID;
        } else {
            throw new \InvalidArgumentException("No ClientID Provided.");
        }
    }
    
    public function getClientID()
    {
        return $this->clientID;
    }
    
    public function setClientSecret($clientSecret)
    {

        $this->clientSecret = $clientSecret;
    }
    
    public function getClientSecret()
    {
        return !empty($this->clientSecret) ? $this->clientSecret : null;
    }
    
    public function setAuthUrlCallback($authCallback)
    {
        $this->authUrlCallback = $authCallback;
    }
    
    public function getAuthUrlCallback()
    {
        return !empty($this->authUrlCallback) ? $this->authUrlCallback : null;
    }
    
    public function setToken($token)
    {
        $this->accessToken = $token;
    }
    
    public function getToken()
    {
        return !empty($this->accessToken) ? $this->accessToken : null;
    }
    
    public function hasToken()
    {
        return !empty($this->accessToken) ? true : false;
    }        
    
    public function setScope($scope)
    {
        $this->scope = $scope;
    }
    
    public function getScope()
    {
        return isset($this->scope) ? $this->scope : null;
    }
    
    public function setExpires($expires)
    {
        $this->expires = $expires;
    }
    
    public function getExpires()
    {
        return isset($this->expires) ? $this->expires : null;
    }
    
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }
    
    public function getRefreshToken()
    {
        return isset($this->refreshToken) ? $this->refreshToken : null;
    }
    
}

