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
        $file = $this->getCurlFile($trackPath);
        $params = array_merge($params, array('track[asset_data]' => $file));
        $finalParams = $this->mergeAuthParams($params);
        
        return $this->post('/tracks')->setParams($finalParams)->request();
    }
    
    /**
     * @param string $trackPath the full path for the media file to upload.
     * @return mixed \CURLFile object if CurlFile class available or string prepended with @ for deprecated file upload.
     */
    private function getCurlFile($trackPath)
    {
        if (class_exists('CurlFile') === true) {
            return new \CURLFile($trackPath);
        }
        
        return "@" . $trackPath;
    }
    
}
