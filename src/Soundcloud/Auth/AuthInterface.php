<?php

namespace Njasm\Soundcloud\Auth;

interface AuthInterface
{
    /**
     * @param string $clientID Soundcloud Client id.
     * @return void
     */
    public function setClientID($clientID);
    
    /**
     * @return string Soundcloud Client id.
     * @return string
     */
    public function getClientID();
    
    /**
     * @return string|null Client Secret string if set, else null is returned.
     */
    public function getClientSecret();
    
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
     * @return void
     */
    public function setToken($token);
    
    /**
     * @param string $scope Authorization Scope.
     * @return void
     */
    public function setScope($scope);
    
    /**
     * @return string|null Authorization Scope.
     */
    public function getScope();
    
    /**
     * @param int $expire Expire time.
     * @return void
     */
    public function setExpires($expire);
    
    /**
     * @return int|null Expire time, null if not set.
     */
    public function getExpires();
    
    /**
     * @param string $token Refresh Token.
     * @return void
     */
    public function setRefreshToken($token);
    
    /**
     * @return string|null The refresh token, null if not set.
     */
    public function getRefreshToken();
}
