<?php

namespace Njasm\Soundcloud;

use Njasm\Soundcloud\Soundcloud;

/**
 * SoundCloud API wrapper in PHP
 *
 * @author      Nelson J Morais <njmorais@gmail.com>
 * @copyright   2014 Nelson J Morais <njmorais@gmail.com>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @link        http://github.com/njasm/soundcloud
 * @package     Njasm\Soundcloud
 */

class SoundcloudFacade extends Soundcloud
{   
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
     * Request for a valid access token via User Credential Flow
     * 
     * @param string $username user username
     * @param string $password user password
     * @return \Njasm\Soundcloud\Request\ResponseInterface
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
        $response = $this->post('/oauth2/token', $params)->asJson()
            ->request(array(
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded')
            ))->bodyObject();
        $this->setAuthData($response);

        return $this->response;
    }
    
    /**
     * Second step in user authorization. 
     * Exchange code for token
     * 
     * @param string $code the code received to exchange for token
     * @param array $params 
     * @return \Njasm\Soundcloud\Request\ResponseInterface
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
        $response = $this->post('/oauth2/token', $finalParams)->asJson()
            ->request(array(
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded')
            ))->bodyObject();
        $this->setAuthData($response);
        
        return $this->response;
    }
    
    /**
     * Refresh Auth access token.
     * 
     * @param string|null $refreshToken the refresh token to send to soundcloud. if null, the default Auth object
     *                                  refresh token will be used.
     * @param array $params 
     * @return \Njasm\Soundcloud\Request\ResponseInterface
     */    
    public function refreshAccessToken($refreshToken = null, array $params = array())
    {
        $defaultParams = array(
            'redirect_uri'  => $this->auth->getAuthUrlCallback(),
            'client_id'     => $this->auth->getClientID(),
            'client_secret' => $this->auth->getClientSecret(),
            'grant_type'    => 'refresh_token',
            'refresh_token' => ($refreshToken) ?: $this->auth->getRefreshToken()
        );
        
        $finalParams = array_merge($defaultParams, $params);
        $response = $this->post('/oauth2/token', $finalParams)->asJson()
            ->request(array(
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded')
            ))->bodyObject();
        $this->setAuthData($response);
        
        return $this->response;
    }
    
    /**
     * Sets OAuth data received from Soundcloud into Auth object.
     * 
     * @param \stdClass $response
     * @return void
     */
    protected function setAuthData($response)
    {
        $accessToken    = isset($response->access_token) ? $response->access_token : null;
        $scope          = isset($response->scope) ? $response->scope : null;
        $expires        = isset($response->expires_in) ? $response->expires : null;
        $refreshToken   = isset($response->refresh_token) ? $response->refresh_token : null;

        $this->auth->setToken($accessToken);
        $this->auth->setScope($scope);
        $this->auth->setExpires($expires);
        $this->auth->setRefreshToken($refreshToken);
    }
    
    /**
     * Download a track from soundcloud.
     * 
     * @param integer track ID.
     * @param boolean $download if we should follow location and download the media file to an in-memory variable 
     *                          accessible on the Response::bodyRaw() method, or return the Response object with the
     *                          location header with the direct URL.
     * @return \Njasm\Soundcloud\Request\ResponseInterface
     */
    public function download($trackID, $download = false)
    {
        $path = '/tracks/' . intval($trackID) . '/download';
        $this->get($path);

        if ($download === true) {
            $this->request(array(CURLOPT_FOLLOWLOCATION => true));
        } else {
            $this->request(array(CURLOPT_FOLLOWLOCATION => false));
        }
        
        return $this->response;        
    }
    
    /**
     * Upload a track to soundcloud.
     * 
     * @param string $trackPath the path to the media file to be uploaded to soundcloud.
     * @param array $params the params/info for the track that will be uploaded like, licence, name, etc.
     * @return \Njasm\Soundcloud\Request\ResponseInterface
     */
    public function upload($trackPath, array $params = array())
    {
        // loop to keep BC. params array can be
        // array('track[title]' => 'track name', ...) or
        // array('title' => 'track name', 'downloadable' => true, ...)
        foreach($params as $key => $value) {
            if (stripos($key, 'track[') !== false) {
                continue;
            }
            $params['track[' . $key . ']'] = $value;
            unset($params[$key]);
        }

        $file = $this->getCurlFile($trackPath);
        $params = array_merge($params, array('track[asset_data]' => $file));
        $finalParams = $this->mergeAuthParams($params);
        
        return $this->post('/tracks')->setParams($finalParams)
            ->request(array(CURLOPT_HTTPHEADER => array('Content-Type: multipart/form-data')));
    }
    
    /**
     * @param string $trackPath the full path for the media file to upload.
     * @return string|\CURLFile object if CurlFile class available or string prepended with @ for deprecated file upload.
     */
    private function getCurlFile($trackPath)
    {
        if (class_exists('CurlFile') === true) {
            return new \CURLFile($trackPath);
        }
        
        return "@" . $trackPath;
    }
    
}
