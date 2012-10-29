<?php
require_once 'Soundcloud_Exception.php';

/**
 * SoundCloud API wrapper (Unfinished!!!)
 *
 * @author      Nelson J Morais <njmorais@gmail.com>
 * @copyright   2012 Nelson J Morais <njmorais@gmail.com>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @link        http://github.com/njasm/soundcloud
 * @category    Services
 * @package     Soundcloud Unfinished
 * @todo        Get this wrapper to BETA version!
 */
Class Soundcloud 
{
    /**
     * Soundcloud api Client ID
     * 
     * @var string
     * @access private
     * @static
     */
    private static $clientId;
    
    /**
     * Soundcloud api Client Secret
     * 
     * @var string
     * @access private
     * @static
     */
    private static $clientSecret; 
    
    /**
     * Soundcloud api End User Authorization
     * 
     * @var string
     * @access private
     * @static
     */
    private static $redirectUri;
    
    /**
     * Response code for authenticating Oauth2
     * @var string
     */
    private static $responseType;
    
    /**
     * Soundcloud api Oauth2 Token
     * 
     * @var string
     * @access private
     * @static
     */
    private static $oauth_token;  
    
    /**
     * Base URL endpoint for accessing Soundcloud.com API
     * using SSL
     * 
     * @var string Soundcloud api URL
     * @access private
     */
    private $_baseURL = 'soundcloud.com';
    
    
    /**
     * Soundcloud Response Type.
     * 
     * @var string
     * @access private
     */
    private $_responseType = 'json';
     
    /**
     * Available Response Types.
     *
     * @var array
     * @access private
     */
    private $_responseTypes = array(
        'json' => 'application/json',
        'xml' => 'application/xml'
    );
    
    /**
     * cURL Options Default.
     * 
     * @var array 
     * @access private
     */
    private $_curlOptionsDefault = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
    );
    /**
     * cURL Options.
     * 
     * @var array 
     * @access private
     */
    private $_curlOptions = array();
    
    /**
     * cURL Resource handler
     * 
     * @var Object
     * @access private
     */
    private $_curl;
    
    /**
     * cURL response object
     * 
     * @var Objec cUrl response object
     * @access public
     */
    public $scResponse;
    
    /**
     * __construct() method
     * Setting Client ID only for now
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
     * Public Method - This is the method users shoud use to access Soundcloud api resources
     * 
     * @return object the response received from Soundcloud API
     * @access private
     * 
     */
    public function getResource($resource, $params = array()) {

        $url = $this->_buildUrl($resource, $params);
        
        $this->setCurlOptions(array(CURLOPT_URL => $url));
    
        $this->buildCurl();
    
        return $this->scResponse;
    }
       
    /**
     * Private Method to Build cURL and make the Request and get
     * soundcloud api server response.
     * 
     * @access private
     * 
     */
    private function buildCurl() {

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
        if (curl_getinfo($this->_curl, CURLINFO_HTTP_CODE) === 401) {
            //curl_getinfo($this->_curl, CURLINFO_HTTP_CODE);
        }  
        
        // close cURL resource
        curl_close($this->_curl);
    }

	/**
	 *  Build a URL
	 * 
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
		
        return $url;        
	}
    
    /**
     * Build Authorization URL 
     * 
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
     * 
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
        
        $this->buildCurl();
        return $this->scResponse;
    }
    
    /**
     * Set Access Token
     * 
     */
    public function setAccessToken($code) {
        self::$oauth_token = $code;
        
        return $this;
    }
    
    /**
     * Public method to set cURL Options
     * 
     * The method accepts associative array data.
     * 
     * Example:
     * <code>
     * $soundcloud->setCurlOptions(array(
     *      CURLOPT_HEADER => false,
     *      CURLOPT_SSL_VERIFYPEER => false
     * ));
     * </code>
     * 
     * @return object
     * @access public
     * 
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
     *
     * @return array
     * @access public
     */    
    public function getCurlOptions() {
        return $this->_curlOptions;
    }

    /**
     * Public method to set Response Type
     * 
     * Example:
     * <code>
     * $soundcloud->setResponseType('json');
     * </code>
     * 
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
                // need better error handling.
                // do nothing and use default json, or throw Excpetion?!
                // for now we go default json response.
                //echo 'Unavailable Response Type: ' . func_get_arg(0);
        }
      
        return $this;
    }
    
    /**
     * Public method to get Soundcloud api ResponseType
     * 
     * @return string
     * @access public
     */
    public function getResponseType() {
        return $this->_responseTypes[$this->_responseType];
    }
}
?>
