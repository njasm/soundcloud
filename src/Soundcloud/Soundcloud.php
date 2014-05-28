<?php

namespace Njasm\Soundcloud;

use Njasm\Soundcloud\Request\RequestInterface;
use Njasm\Soundcloud\Exception\SoundcloudException;
use Njasm\Soundcloud\Factory\Factory;

/**
 * SoundCloud API wrapper in PHP
 *
 * @author      Nelson J Morais <njmorais@gmail.com>
 * @copyright   2014 Nelson J Morais <njmorais@gmail.com>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @link        http://github.com/njasm/soundcloud
 * @package     Njasm\Soundcloud
 */

class Soundcloud
{
    private $resource;
    private $request;
    private $response;
    private $auth;
    private $factory;
    private $responseFormat;

    public function __construct($clientID = null, $clientSecret = null, $authCallbackUri = null)
    {
        $this->factory = new Factory();
        $this->auth = $this->make('AuthInterface', array($clientID, $clientSecret, $authCallbackUri));
    }
    
    /**
     * Get ClientID for this instance
     * 
     * @return string The ClientID set for this instance
     */
    public function getAuthClientID()
    {
        return $this->auth->getClientID();
    }
    
    /**
     * Get the access token.
     * 
     * @return mixed the token, else null is returned
     */
    public function getAuthToken()
    {
        return $this->auth->getToken();
    }
    
    /**
     * Get the token scope.
     * 
     * @return mixed the scope for this access token, null if empty
     */
    public function getAuthScope()
    {
        return $this->auth->getScope();
    }
    
    /**
     * Sets up a GET Resource.
     * 
     * @param string $path
     * @param array $params
     * @return \Njasm\Soundcloud\Soundcloud
     */
    public function get($path, array $params = array())
    {
        $params = $this->mergeAuthParams($params);
        $this->resource = $this->make('ResourceInterface', array('get', $path, $params));
        return $this;
    }

    /**
     * Sets up a PUT Resource.
     * 
     * @param string $path
     * @param array $params
     * @return \Njasm\Soundcloud\Soundcloud
     */
    public function put($path, array $params = array())
    {
        $params = $this->mergeAuthParams($params);
        $this->resource = $this->make('ResourceInterface', array('put', $path, $params));
        return $this;
    }
    
    /**
     * Sets up a POST Resource.
     * 
     * @param string $path
     * @param array $params
     * @return \Njasm\Soundcloud\Soundcloud
     */
    public function post($path, array $params = array())
    {
        $params = $this->mergeAuthParams($params);
        $this->resource = $this->make('ResourceInterface', array('post', $path, $params));
        return $this;
    }
    
    /**
     * Sets up a DELETE Resource.
     * 
     * @param string $path
     * @param array $params
     * @return \Njasm\Soundcloud\Soundcloud
     */
    public function delete($path, array $params = array())
    {
        $params = $this->mergeAuthParams($params);
        $this->resource = $this->make('ResourceInterface', array('delete', $path, $params));
        return $this;
    }
    
    /**
     * @param string $interface the interface to build
     * @param array $params the interface object dependencies
     * @return object
     */
    private function make($interface, array $params = array())
    {
        return $this->factory->make($interface, $params);
    }
    
    /**
     * Sets resource params.
     * 
     * @param array $params
     * @return \Njasm\Soundcloud\Soundcloud
     * @throws SoundcloudException
     */
    public function setParams(array $params = array())
    {
        if (!isset($this->resource)) {
            throw new SoundcloudException("No Resource found. you must call a http verb method before " . __METHOD__);
        }
        
        $this->resource->setParams($params);
        
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
            'client_id'     => $this->auth->getClientID(),
            'scope'         => 'non-expiring',
            'display'       => 'popup',
            'response_type' => 'code',
            'redirect_uri'  => $this->auth->getAuthUrlCallback(),
            'state'         => ''
        );
        
        $params = array_merge($defaultParams, $params);
        $resource = $this->make('ResourceInterface', array('get', '/connect', $params));
        $url = $this->make('UrlBuilderInterface', array($resource, 'www'));
        
        return $url->getUrl();
    }
    
    /**
     * Second step in user authorization. 
     * Exchange code for token
     * 
     * @param string $code the code received to exchange for token
     * @param array $params 
     * @return Njasm\Soundcloud\Request\ResponseInterface
     */
    public function codeForToken($code, array $params = array())
    {
        $defaultParams = array(
            'redirect_uri'  => $this->auth->getAuthUrlCallback(),
            'grant_type'    => 'authorization_code',
            'code'          => $code
        );
        
        $mergedParams = array_merge($defaultParams, $params);
        $finalParams = $this->mergeAuthParams($mergedParams, true);
        $this->resource = $this->make('ResourceInterface', array('post', '/oauth2/token', $finalParams));
        
        $response = $this->request()->bodyObject();
        
        if (isset($response->access_token)) {
            $this->auth->setToken($response->access_token);
        }
        
        return $this->response;
    }
    
    /**
     * Request for a valid access token via User Credential Flow
     * 
     * @param string $username user username
     * @param string $password user password
     * @return Njasm\Soundcloud\Request\ResponseInterface
     */
    public function userCredentials($username, $password)
    {
        $defaultParams = array(
            'grant_type'    => 'password',
            'scope'         => 'non-expiring',
            'username'      => $username,
            'password'      => $password
        );
        
        $params = $this->mergeAuthParams($defaultParams, true);
        $this->resource = $this->make('ResourceInterface', array('post', '/oauth2/token', $params));
        
        $response = $this->request()->bodyObject();
        
        if (isset($response->access_token)) {
            $this->auth->setToken($response->access_token);
        }
        
        return $this->response;
    }
    
    /**
     * Download a track.
     * 
     * @param integer track ID.
     * @param boolean $redirectWebUser if we should redirect the user, sending a header('Location: track_url');
     * @return mixed An object with the download location, or redirect user to that Location.
     */
    public function download($trackID, $redirectWebUser = true)
    {
        $path = '/tracks/' . intval($trackID) . '/download';
        $this->resource = $this->make('ResourceInterface', array('get', $path));

        if ($redirectWebUser) {
            $this->request();
            header('Location: ' . $this->response->getHeader('Location'));
        } else {
            $this->request(array(CURLOPT_FOLLOWLOCATION => true));
            return $this->response;
        }
    }
    
    /**
     * Executes the request against soundcloud api.
     * 
     * @param array $params
     * @return Njasm\Soundcloud\Request\ResponseInterface
     */
    public function request(array $params = array())
    {
        $urlBuilder = $this->make('UrlBuilderInterface', array($this->resource));
        $this->request = $this->make('RequestInterface', array($this->resource, $urlBuilder, $this->factory));
        $this->request->setOptions($params);
        $this->setResponseFormat($this->request);
        
        $this->response = $this->request->exec();
        
        return $this->response;
    }
    
    /**
     * Get Last Curl Response object.
     * 
     * @return mixed The Response Object, null if no request was yet made
     */
    public function getCurlResponse()
    {
        return (isset($this->response)) ? $this->response : null;
    }
    
    /**
     * Sets the Accept Header to application/xml.
     * 
     * @return Soundcloud
     */
    public function asXml()
    {
        $this->responseFormat = "xml";
        return $this;
    }
    
    /**
     * Sets the Accept Header to application/json.
     * 
     * @return Soundcloud
     */
    public function asJson()
    {
        $this->responseFormat = "json";
        return $this;
    }
    
    /**
     * Set response format for Request.
     * 
     * @return void
     */
    private function setResponseFormat(RequestInterface $request)
    {
        if ($this->responseFormat == "xml") {
            $request->asXml();
        } else {
            $request->asJson();
        }
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
        if ($token !== null) {
            return array_merge($params, array('oauth_token' => $token));
        }
        
        if ($token === null && $includeClientSecret === false) {
            return array_merge($params, array('client_id' => $this->auth->getClientID()));
        }
        
        return array_merge($params, array(
            'client_id' => $this->auth->getClientID(),
            'client_secret' => $this->auth->getClientSecret()
        ));          
    }
}
