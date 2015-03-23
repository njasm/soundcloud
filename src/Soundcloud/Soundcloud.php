<?php

namespace Njasm\Soundcloud;

use Njasm\Soundcloud\Auth\Auth;
use Njasm\Soundcloud\Factory\AbstractFactory;
use Njasm\Soundcloud\Http\Request;
use Njasm\Soundcloud\Http\Url\UrlBuilder;
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

    protected static $self;

    public function __construct($clientID = null, $clientSecret = null, $authCallbackUri = null)
    {
        $this->auth = new Auth($clientID, $clientSecret, $authCallbackUri);
        self::$self = $this;
    }

    public static function instance()
    {
        if (is_null(self::$self)) {
            throw new \Exception("Soundcloud Service not initialized!");
        }

        return self::$self;
    }


    /**
     * Sets up a PUT Resource.
     * 
     * @param string $url
     * @param array $params
     * @return \Njasm\Soundcloud\Http\RequestInterface
     */
    public function put($url, array $params = [])
    {
        $verb = 'PUT';
        $params = $this->auth()->mergeParams($params);
        $this->request = new Request($verb, $url, $params);

        return $this->request;
    }
    
    /**
     * Sets up a POST Resource.
     * 
     * @param string $url
     * @param array $params
     * @return \Njasm\Soundcloud\Http\RequestInterface
     */
    public function post($url, array $params = [])
    {
        $verb = 'POST';
        $params = $this->auth()->mergeParams($params);
        $this->request = new Request($verb, $url, $params);

        return $this->request;
    }
    
    /**
     * Sets up a DELETE Resource.
     * 
     * @param string $url
     * @param array $params
     * @return \Njasm\Soundcloud\Http\RequestInterface
     */
    public function delete($url, array $params = [])
    {
        $verb = 'DELETE';
        $params = $this->auth()->mergeParams($params);
        $this->request = new Request($verb, $url, $params);

        return $this->request;
    }

    /**
     * Sets up a GET Resource.
     *
     * @param string $url
     * @param array $params
     * @return \Njasm\Soundcloud\Soundcloud
     */
    public function get($url, array $params = [])
    {
        $verb = 'GET';
        $params = $this->auth()->mergeParams($params);
        $url = UrlBuilder::getUrl($verb, $url, $params);
        $this->request = new Request($verb, $url, $params);

        return $this->request;
    }

    public function getMe()
    {
        $verb = 'GET';
        $params = $this->mergeAuthParams();
        $url = 'https://api.soundcloud.com/me';
        $url = UrlBuilder::getUrl($verb, $url, $params);

        $this->request = new Request($verb, $url, $params);
        $this->response = $this->request->send();

        return AbstractFactory::unserialize($this->response->bodyRaw());
    }

    /**
     * @return \Njasm\Soundcloud\Auth\AuthInterface
     * @since 3.0.0
     */
    public function auth()
    {
        return $this->auth;
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
