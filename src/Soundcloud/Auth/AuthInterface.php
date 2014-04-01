<?php

namespace Njasm\Soundcloud\Auth;

interface AuthInterface {
    public function __construct($clientID = null, $clientSecret = null, $authCallback = null);
    public function setClientID($clientID = null);
    public function getClientID();
    public function setClientSecret($clientSecret = null);
    public function getClientSecret();
    public function setAuthUrlCallback($authCallback = null);
    public function getToken();
    public function setToken($token = null);
    public function setScope($scope = null);
    public function getScope();
    public function setExpires($expire = null);
    public function getExpires();
    public function setRefreshToken($token = null);
    public function getRefreshToken();
}
