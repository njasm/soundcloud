<?php

namespace Njasm\Soundcloud;

use Njasm\Soundcloud\Auth\Auth;
use Njasm\Soundcloud\Factory\AbstractFactory;
use Njasm\Soundcloud\Http\Request;
use Njasm\Soundcloud\Http\Url\UrlBuilder;

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
        $params = $this->auth->mergeParams($params);
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
        $params = $this->auth->mergeParams($params);
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
        $params = $this->auth->mergeParams($params);
        $this->request = new Request($verb, $url, $params);

        return $this->request;
    }

    /**
     * Sets up a GET Resource.
     *
     * @param string $url
     * @param array $params
     * @return \Njasm\Soundcloud\Http\RequestInterface
     */
    public function get($url, array $params = [])
    {
        $verb = 'GET';
        $params = $this->auth->mergeParams($params);
        $this->request = new Request($verb, $url, $params);

        return $this->request;
    }

    public function getMe()
    {
        $verb = 'GET';
        $url = '/me';
        $params = $this->auth->mergeParams();
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
     * Request for a valid access token via User Credential Flow
     *
     * @param string $username user username
     * @param string $password user password
     * @return \Njasm\Soundcloud\Http\ResponseInterface
     */
    public function userCredentials($username, $password)
    {
        $defaultParams = [
            'grant_type'    => 'password',
            'scope'         => 'non-expiring',
            'username'      => $username,
            'password'      => $password
        ];

        $params = $this->auth->mergeParams($defaultParams, true);
        $this->request = $this->post('/oauth2/token', $params);
        $this->response = $this->request->send();
        $response = $this->response->bodyObject();
        $this->setAuthData($response);

        return $this->response;
    }

    /**
     * Second step in user authorization.
     * Exchange code for token
     *
     * @param string $code the code received to exchange for token
     * @param array $params
     * @return \Njasm\Soundcloud\Http\ResponseInterface
     */
    public function codeForToken($code, array $params = [])
    {
        $defaultParams = [
            'redirect_uri'  => $this->auth->getAuthUrlCallback(),
            'grant_type'    => 'authorization_code',
            'code'          => $code
        ];

        $mergedParams = array_merge($defaultParams, $params);
        $finalParams = $this->auth->mergeParams($mergedParams, true);
        $this->request = $this->post('/oauth2/token', $finalParams);
        $this->response = $this->request->send();
        $response = $this->response->bodyObject();
        $this->setAuthData($response);

        return $this->response;
    }

    /**
     * Refresh Auth access token.
     *
     * @param string|null $refreshToken the refresh token to send to soundcloud. if null, the default Auth object
     *                                  refresh token will be used.
     * @param array $params
     * @return \Njasm\Soundcloud\Http\ResponseInterface
     */
    public function refreshAccessToken($refreshToken = null, array $params = [])
    {
        $defaultParams = [
            'redirect_uri'  => $this->auth->getAuthUrlCallback(),
            'client_id'     => $this->auth->getClientID(),
            'client_secret' => $this->auth->getClientSecret(),
            'grant_type'    => 'refresh_token',
            'refresh_token' => ($refreshToken) ?: $this->auth->getRefreshToken()
        ];

        $finalParams = array_merge($defaultParams, $params);
        $this->request = $this->post('/oauth2/token', $finalParams);
        $this->response = $this->request->send();
        $response = $this->response->bodyObject();
        $this->setAuthData($response);

        return $this->response;
    }

    /**
     * Sets OAuth data received from Soundcloud into Auth object.
     *
     * @param stdClass $response
     * @return void
     */
    protected function setAuthData($response)
    {
        $accessToken    = isset($response->access_token) ? $response->access_token : null;
        $scope          = isset($response->scope) ? $response->scope : null;
        $expires        = isset($response->expires_in) ? $response->expires_in : null;
        $refreshToken   = isset($response->refresh_token) ? $response->refresh_token : null;

        $this->auth->setToken($accessToken);
        $this->auth->setScope($scope);
        $this->auth->setExpires($expires);
        $this->auth->setRefreshToken($refreshToken);
    }

    /**
     * Get the authorization url for your users.
     *
     * @param array $params key => value pair, of params to be sent to the /connect endpoint.
     * @return string The URL
     */
    public function getAuthUrl(array $params = [])
    {
        $defaultParams = [
            'client_id'     => $this->auth->getClientID(),
            'scope'         => 'non-expiring',
            'display'       => 'popup',
            'response_type' => 'code',
            'redirect_uri'  => $this->auth->getAuthUrlCallback(),
            'state'         => ''
        ];

        $params = array_merge($defaultParams, $params);

        return UrlBuilder::getUrl('GET', 'https://soundcloud.com/connect', $params);
    }

    public function resolve($what)
    {
        $url = '/resolve';
        $params['url'] = (string) $what;
        $params = $this->auth->mergeParams($params);
        $this->request = $this->get($url, $params);
        $this->response = $this->request->send();

        return AbstractFactory::unserialize($this->response->bodyRaw());
    }
}

