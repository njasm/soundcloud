<?php

namespace Njasm\Soundcloud;

use Njasm\Soundcloud\Resource\Resource;
use Njasm\Soundcloud\UrlBuilder\UrlBuilder;
use Njasm\Soundcloud\Request\Request;
use Njasm\Soundcloud\Auth\Auth;
use Njasm\Soundcloud\Exception\SoundcloudException;

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
 */
Class Soundcloud {
    
    private $resource;
    private $request;
    private $response;
    private $urlBuilder;
    private $auth;
    
    private $responseFormat;

    public function __construct($clientID = null, $clientSecret = null, $authCallbackUri = null)
    {
        $this->auth = new Auth($clientID, $clientSecret, $authCallbackUri);
    }
    
    /**
     * Auth Direct Methods
     */
    public function setAuthClientID($clientID)
    {
        $this->auth->setClientID($clientID);
        return $this;
    }
    
    public function getAuthClientID()
    {
        return $this->auth->getClientID();
    }
    
    public function getAuthToken()
    {
        return $this->auth->getToken();
    }
    
    public function setAuthToken($token)
    {
        $this->auth->setToken($token);
        return $this;
    }
    
    public function getAuthScope()
    {
        return $this->auth->getScope();
    }
    
    public function setAuthScope($scope)
    {
        $this->auth->setScope($scope);
        return $this;
    }
    
    /**
     * Sets up a GET Resource.
     * 
     * @param string $path
     * @param array $params
     * @return \Njasm\Soundcloud\Soundcloud Soundcloud
     */
    public function get($path, array $params = array())
    {
        $params = $this->mergeAuthParams($params);
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
    public function put($path, array $params = array())
    {
        $params = $this->mergeAuthParams($params);        
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
    public function post($path, array $params = array())
    {
        $params = $this->mergeAuthParams($params);
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
        $params = $this->mergeAuthParams($params);        
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
        if (is_object($this->resource)) {
            $this->resource->setParams($params);
        } else if (!isset($this->resource)){
            throw new SoundcloudException("No Resource found. you must call a http verb method before " . __METHOD__);
        }
        
        return $this;
    }
    
    /**
     * Get the authorization url for your users.
     * 
     * @param array $params key => value pair, of params to be sent to the /connect endpoint.
     * @return string The URL
     */
    public function getAuthUrl(array $params = array())
    {
        $defaultParams = array(
            'client_id' => $this->auth->getClientID(),
            'scope' => 'non-expiring',
            'display' => 'popup',
            'response_type' => 'token_and_code',
            'redirect_uri' => $this->auth->getAuthUrlCallback(),
            'state' => ''
        );
        
        $params = array_merge($defaultParams, $params);
        $resource = Resource::get("/connect", $params);
        $url = new UrlBuilder($resource, "www");
        return $url->getUrl();
    }
    
    /**
     * Request for a valid access token via User Credential Flow
     * 
     * @param string $username user username
     * @param string $password user password
     */
    public function getTokenViaUserCredentials($username, $password) 
    {
        $username = trim($username);
        $password = trim($password);
        $params = array(
            'grant_type' => 'password',
            'scope' => 'non-expiring',
            'username' => $username,
            'password' => $password                
        );
        
        $params = $this->mergeAuthParams($params, true);
        $this->resource = Resource::post("/oauth2/token", $params);
        $this->urlBuilder = new UrlBuilder($this->resource);
        $this->request = new Request($this->resource, $this->urlBuilder);
        $this->response = $this->request->exec();
        
        return $this->response;
    }
    
    public function asXml()
    {
        $this->responseFormat = "xml";
        return $this;
    }
    
    public function asJson()
    {
        $this->responseFormat = "json";
        return $this;
    }
    
    public function request(array $params = array())
    {
        $this->urlBuilder = new UrlBuilder($this->resource);
        $this->request = new Request($this->resource, $this->urlBuilder);
        if (!empty($params)) {
            $this->request->setOptions($params);
        }
        
        // set response format
        if ($this->responseFormat == "xml") {
            $this->request->asXml();
        } else if ($this->responseFormat == "json") {
            $this->request->asJson();
        }
        
        return $this->request->exec();
    }  
    
    /**
     * Manage auth values for requests.
     * 
     * @param array $params
     * @param bool $includeClientSecret
     * @return array
     */
    private function mergeAuthParams(array $params = array(), $includeClientSecret = false)
    {     
        $token = $this->auth->getToken();
        if ($token) {
            $params = array_merge($params, array('oauth_token' => $token));
        } else {
            if ($includeClientSecret) {
                $params = array_merge($params, array(
                    'client_id' => $this->auth->getClientID(),
                    'client_secret' => $this->auth->getClientSecret()
                ));
            } else {
                $params = array_merge($params, array(
                    'client_id' => $this->auth->getClientID()
                ));
            }
        }
        
        return $params;
    }
    
}