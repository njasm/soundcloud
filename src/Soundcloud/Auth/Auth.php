<?php

namespace Njasm\Soundcloud\Auth;

use Njasm\Soundcloud\Auth\AuthInterface;

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
        
        $this->authUrlCallback = $authUrlCallback;
    }
    
    public function setClientID($clientID = null)
    {
        if (is_string($clientID)) {
            $this->clientID = $clientID;
        } else {
            throw new \InvalidArgumentException("No ClientID Provided.");
        }
    }
    
    public function getClientID()
    {
        return $this->clientID;
    }
    
    public function setClientSecret($clientSecret = null)
    {
        // we might not have a client secret
        $this->clientSecret = $clientSecret;
    }
    
    public function getClientSecret()
    {
        return $this->clientSecret;
    }
    
    public function setAuthUrlCallback($authCallback = null)
    {
        // we might not have a authUrlCallback
        $this->authUrlCallback = $authCallback;
    }
    
    public function getAuthUrlCallback()
    {
        return $this->authUrlCallback;
    }
    
    public function setToken($token = null)
    {
        $this->accessToken = $token;
    }
    
    public function getToken()
    {
        return $this->accessToken;
    }
    
    public function setScope($scope = null)
    {
        $this->scope = $scope;
    }
    
    public function getScope()
    {
        return isset($this->scope) ? $this->scope : null;
    }
    
    public function setExpires($expires = null)
    {
        $this->expires = $expires;
    }
    
    public function getExpires()
    {
        return isset($this->expires) ? $this->expires : null;
    }
    
    public function setRefreshToken($refreshToken = null)
    {
        $this->refreshToken = $refreshToken;
    }
    
    public function getRefreshToken()
    {
        return isset($this->refreshToken) ? $this->refreshToken : null;
    }
    
}

