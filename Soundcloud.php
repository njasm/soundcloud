<?php
require_once 'Soundcloud_Exception.php';

/**
 * SoundCloud API wrapper
 *
 * @author      Nelson J Morais <njmorais@gmail.com>
 * @copyright   2012 Nelson J Morais <njmorais@gmail.com>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @link        http://github.com/njasm/soundcloud
 * @category    Services
 * @package     Soundcloud Unfinished
 * @todo        Implementation of PUT and DELETE features
 */
Class Soundcloud 
{
    /**
     * Soundcloud api Client ID
     * @var string
     * @access private
     * @static
     */
    private static $clientId;
    
    /**
     * Soundcloud api Client Secret
     * @var string
     * @access private
     * @static
     */
    private static $clientSecret; 
    
    /**
     * Soundcloud api End User Authorization
     * @var string
     * @access private
     * @static
     */
    private static $redirectUri;
    
    /**
     * Response code for authenticating Oauth2
     * @var string
     * @static
     */
    private static $responseType;
    
    /**
     * Soundcloud api Oauth2 Token
     * @var string
     * @access private
     * @static
     */
    private static $oauth_token;  
    
    /**
     * Base URL endpoint for accessing Soundcloud.com
     * @var string Soundcloud api URL
     * @access private
     */
    private $_baseURL = 'soundcloud.com';  
    
    /**
     * Soundcloud Response Type - Defaults to json
     * @var string
     * @access private
     */
    private $_responseType = 'json';
     
    /**
     * Available Response Types.
     * @var array
     * @access private
     */
    private $_responseTypes = array(
        'json' => 'application/json',
        'xml' => 'application/xml'
    );
    
    /**
     * cURL Options Default.
     * @var array 
     * @access private
     */
    private $_curlOptionsDefault = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
    );
    
    /**
     * cURL Options.
     * @var array 
     * @access private
     */
    private $_curlOptions = array();
    
    /**
     * cURL Resource handler
     * @var Object
     * @access private
     */
    private $_curl;
    
    /**
     * cURL response object
     * @var Objec cUrl response object
     * @access public
     */
    public $scResponse;
    
    /**
     * __construct() method
     * 
     */
    public function __construct() {
        
        if (func_get_args(0))
            self::$clientId = func_get_arg(0);
        
        if (func_get_arg(1))
            self::$clientSecret = func_get_arg(1);
        
        if (func_get_arg(2))
            self::$redirectUri = func_get_arg(2);
            
        self::$responseType = 'code';
        $this->_curlOptions = $this->_curlOptionsDefault;
    }
    
    /**
     * Public Method - This is the method users shoud use to access GET Soundcloud api resources
     * @return object the response received from Soundcloud API
     * @access private
     */
    public function getResource($resource, $params = array()) {
        $url = $this->_buildUrl($resource, $params);      
        $this->setCurlOptions(array(CURLOPT_URL => $url));    
        $this->_buildCurl();
    
        return $this->scResponse;
    }
    
    /**
     * Public Method - This is the method users shoud use to access POST Soundcloud api resources
     * @return object the response received from Soundcloud API
     * @access private
     */
    public function postResource($resource, $params = array()) {
        $url = $this->_buildUrl($resource); 

        // set cURL to Post and add url
        $this->setCurlOptions(array(
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_URL => $url));
    
        $this->_buildCurl();
    
        return $this->scResponse;
    }
    
    /**
     * Public Method to download track method.
     * @access public
     */
    public function download ($resource) {
        $url = $this->_buildUrl($resource);
        
        $this->setCurlOptions(array(
            CURLOPT_URL => $url,
        ));

        $this->_buildCurl();
        
        // redirect user to download link provided by soundcloud.
        header('Location: ' . $this->scResponse->location);

    }
       
    /**
     * Private Method to Build cURL and make the Request and get
     * soundcloud api server response.
     * @access private
     */
    private function _buildCurl() {
        // init curl
        $this->_curl = curl_init();
        
        // set response type, xml or json - defaults to json
        $this->setCurlOptions(array(
            CURLOPT_HTTPHEADER => array('Accept: ' . $this->_responseTypes[$this->_responseType]),
            ));
        
        curl_setopt_array($this->_curl, $this->getCurlOptions());
                       
        // build response object from xml or json ?!
        if ($this->_responseType == 'json') {
            $this->scResponse = json_decode(curl_exec($this->_curl)); 
        } elseif ($this->_responseType == 'xml') {
            $this->scResponse = simplexml_load_string(curl_exec($this->_curl));
        }
        
        // check for curl errors
        if (curl_errno($this->_curl)) {
        // needs better error handling
            echo 'Something went wrong: ' . curl_errno($this->_curl) . ' - ' . curl_error($this->_curl);
        }
     
        // now check soundcloud api http code for errors
        // needs better error handling
        if (curl_getinfo($this->_curl, CURLINFO_HTTP_CODE) === 401) {
            //curl_getinfo($this->_curl, CURLINFO_HTTP_CODE);
        }  
        
        curl_close($this->_curl);
    }

    /**
     *  Build a URL
     * @return string URL string
     * @access private
	 */
    private function _buildUrl($resource, $params = array()) {	
        // is our app already autorized by Soundcloud and
        // do we have an accessToken aready?
        if (!isset(self::$oauth_token)) {
            $params['client_id'] = self::$clientId;
        } else {
            $params['oauth_token'] = self::$oauth_token;
        }
        
        $url = 'https://';
        
        // are we getting an accessToken?
        $url .= (preg_match('/connect/', $resource)) ? '' : 'api.';
        $url .= $this->_baseURL . $resource;
        
        $url .= (count($params) > 0) ? '?' . http_build_query($params) : '';
		echo $url;
        return $url;        
    }
    
    /**
     * Build Authorization URL 
     * @return string The URL
     * @access Public
     */
    public function getAuthUrl() {       
        $url = 'https://' . $this->_baseURL . '/connect?'  
            . 'client_id=' . self::$clientId . '&redirect_uri=' . self::$redirectUri 
            . '&response_type=' . self::$responseType; 
        return $url;
    }
    
    /**
     * Get Access Token
     * @return Object cURL Response
     * @access Public
     */
    public function getAccessToken($code) {
        $postFields = array(
            'client_id' => self::$clientId,
            'client_secret' => self::$clientSecret,
            'grant_type' => 'authorization_code',
            'redirect_uri' => self::$redirectUri,
            'code' => $code,
        );
		$url = 'https://api.' . $this->_baseURL . '/oauth2/token';
        $this->setCurlOptions(array(
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields,
			CURLOPT_URL => $url,
        ));
        
        $this->_buildCurl();
        return $this->scResponse;
    }
    
    /**
     * Set Access Token
     * @return Object 
     * @access Public
     */
    public function setAccessToken($code) {
        self::$oauth_token = $code;
        
        return $this;
    }
    
    /**
     * Public method to set cURL Options
     * The method accepts associative array data.
     * 
     * Example:
     * <code>
     * $soundcloud->setCurlOptions(array(
     *      CURLOPT_HEADER => false,
     *      CURLOPT_SSL_VERIFYPEER => false
     * ));
     * </code>
     * @return object
     * @access public
     */
    public function setCurlOptions() {
        $params = func_get_args();
        
        foreach ($params[0] as $key => $value) {
            $this->_curlOptions[$key] = $value; 
        }
        
        return $this;
    }
    
    /**
     * Public method to get cURL Options
     * @return array
     * @access public
     */    
    public function getCurlOptions() {
        return $this->_curlOptions;
    }

    /**
     * Public method to set Response Type
     * Example:
     * <code>
     * $soundcloud->setResponseType('json');
     * </code>
     * @return object
     * @access public
     */
    public function setResponseType() {
        switch (func_get_arg(0)) {
            case 'xml':
                $this->_responseType = func_get_arg(0);
                break;
            case 'json':
                $this->_responseType = func_get_arg(0);
                break;
            default:
        }
      
        return $this;
    }
    
    /**
     * Public method to get Soundcloud api ResponseType
     * @return string
     * @access public
     */
    public function getResponseType() {
        return $this->_responseTypes[$this->_responseType];
    }
}
?>