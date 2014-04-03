<?php

namespace Njasm\Soundcloud\Auth;

interface AuthInterface {
    
    /**
     * @param string $clientID Soundcloud Client id.
     * @param string $clientSecret Soundcloud Client secret.
     * @param string $authUrlCallBack Authorization url callback.
     */
    public function __construct($clientID = null, $clientSecret = null, $authUrlCallback = null);
    
    /**
     * @param string $clientID Soundcloud Client id.
     */
    public function setClientID($clientID = null);
    
    /**
     * @return string Soundcloud Client id.
     */
    public function getClientID();
    
    /**
     * @param string $clientSecret Soudncloud Client Secret.
     */
    public function setClientSecret($clientSecret);
    
    /**
     * @return string|null Client Secret string if set, else null is returned.
     */
    public function getClientSecret();
    
    /**
     * @param string $authCallback the Callback URL after user authorization at Soundcloud.
     */
    public function setAuthUrlCallback($authCallback);
    
    /**
     * @return string|null Access token, null if not set.
     */
    public function getToken();
    
    /**
     * @return bool true if access token is set, false otherwise.
     */
    public function hasToken();
    
    /**
     * @param string $token Access token.
     */
    public function setToken($token);
    
    /**
     * @param string $scope Authorization Scope.
     */
    public function setScope($scope);
    
    /**
     * @return string|null Authorization Scope.
     */
    public function getScope();
    
    /**
     * @param int $expire Expire time.
     */
    public function setExpires($expire);
    
    /**
     * @return int|null Expire time, null if not set.
     */
    public function getExpires();
    
    /**
     * @param string $token Refresh Token.
     */
    public function setRefreshToken($token);
    
    /**
     * @return string|null The refresh token, null if not set.
     */
    public function getRefreshToken();
}
