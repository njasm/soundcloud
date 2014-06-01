<?php

namespace Njasm\Soundcloud;

use Njasm\Soundcloud\Soundcloud;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SoundcloudFacade
 *
 * @author njasm
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
        $this->resource = $this->make('ResourceInterface', array('post', '/oauth2/token', $params));
        
        $response = $this->request()->bodyObject();
        
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
        $this->resource = $this->make('ResourceInterface', array('post', '/oauth2/token', $finalParams));
        
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
    public function download($trackID, $download = false)
    {
        $path = '/tracks/' . intval($trackID) . '/download';
        $this->resource = $this->make('ResourceInterface', array('get', $path));

        if ($download === true) {
            $this->request(array(CURLOPT_FOLLOWLOCATION => true));
        } else {
            $this->request(array(CURLOPT_FOLLOWLOCATION => false));
        }
        
        return $this->response;        
    }
}
