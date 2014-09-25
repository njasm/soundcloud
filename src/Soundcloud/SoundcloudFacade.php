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
        $response = $this->post('/oauth2/token', $params)->asJson()->request()->bodyObject();
        
        if (isset($response->access_token)) {
            $this->auth->setToken($response->access_token);
        }
        
        return $this->response;
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
        $response = $this->post('/oauth2/token', $finalParams)->asJson()->request()->bodyObject();
        
        if (isset($response->access_token)) {
            $this->auth->setToken($response->access_token);
            $this->auth->setScope($response->scope);
        }
        
        return $this->response;
    }
    
    /**
     * Refresh Auth access token.
     * 
     * @param string $refreshToken the refresh token to send to soundcloud. if null, the default Auth object
     *                             refresh token will be used.
     * @param array $params 
     * @return Njasm\Soundcloud\Request\ResponseInterface
     */    
    public function refreshAccessToken($refreshToken = null, array $params = array())
    {
        $defaultParams = array(
            'redirect_uri'  => $this->auth->getAuthUrlCallback(),
            'client_id'     => $this->auth->getClientID(),
            'client_secret' => $this->auth->getClientSecret(),
            'grant_type'    => 'refresh_token',
            'refresh_token' => (!is_null($refreshToken)) ?: $this->auth->getRefreshToken()
        );
        
        $finalParams = array_merge($defaultParams, $params);
        $response = $this->post('oauth2/token', $finalParams)->asJson()->request()->bodyObject();
        
        if (isset($response->access_token)) {
            $this->auth->setToken($response->access_token);
            $this->auth->setScope($response->scope);
            $this->auth->setExpires($response->expires);
            $this->auth->setRefreshToken($response->refresh_token);
        }
        
        return $this->response;
    }
    
    /**
     * Download a track from soundcloud.
     * 
     * @param integer track ID.
     * @param boolean $download if we should follow location and download the media file to an in-memory variable 
     *                          accessible on the Response::bodyRaw() method, or return the Response object with the
     *                          location header with the direct URL.
     * @return mixed An object with the download location, or redirect user to that Location.
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
     */
    public function upload($trackPath, array $params = array())
    {
        $file = $this->getCurlFile($trackPath);
        $params = array_merge($params, array('track[asset_data]' => $file));
        $params = $this->mergeAuthParams($params);
        
        return $this->post('/tracks')->setParams($params)->request();
    }
    
    /**
     * @param string $trackPath the full path for the media file to upload.
     * @return mixed \CURLFile object if CurlFile class available, else prepend an @ for deprecated file upload.
     */
    private function getCurlFile($trackPath)
    {
        if (class_exists('CurlFile') === true) {
            return new \CURLFile($trackPath);
        }
        
        return "@" . $trackPath;
    }
    
}
