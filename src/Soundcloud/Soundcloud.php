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
    protected $resource;
    protected $request;
    protected $response;
    protected $auth;
    protected $factory;
    protected $responseFormat;

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
     * Get the token scope.
     * 
     * @return mixed the scope for this access token, null if empty
     */
    public function getExpires()
    {
        return $this->auth->getExpires();
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
    protected function make($interface, array $params = array())
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
    protected function setResponseFormat(RequestInterface $request)
    {
        if ($this->responseFormat == "xml") {
            $request->asXml();
        } else {
            $request->asJson();
        }
    }
    
    /**
     * Build array of auth params for the next request.
     * 
     * @param array $params
     * @param bool $includeClientSecret
     * @return array
     */
    protected function mergeAuthParams(array $params = array(), $includeClientSecret = false)
    {
        return $this->auth->mergeParams($params, $includeClientSecret);
    }
}
