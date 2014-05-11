[![Build Status](https://travis-ci.org/njasm/soundcloud.svg?branch=master)](https://travis-ci.org/njasm/soundcloud) [![Coverage Status](https://coveralls.io/repos/njasm/soundcloud/badge.png?branch=master)](https://coveralls.io/r/njasm/soundcloud?branch=master) [![Dependency Status](https://www.versioneye.com/user/projects/534af6adfe0d078843000029/badge.png)](https://www.versioneye.com/user/projects/534af6adfe0d078843000029)
### Soundcloud.com API Wrapper in PHP
This is still under development.
Design decisions are still being made, and still needs further testing.
If you want a stable version, have fun with the master TAG 0.0.1 :)

#### Implemented features 

* User Authorization/Authentication
* User Credentials Flow Authentication
* Access to all GET, PUT, POST and DELETE Resources

#### Todo

* File Download
* File Upload

##### Examples
###### Get Authorization Url.
```php
$facade = new Soundcloud($clientID, $clientSecret, $callbackUri);
$url = $facade->getAuthUrl();

// or inject your own params
$url = $facade->getAuthUrl(array(
    'response_type' => 'code',
    'scope' => '*',
    'state' => 'my_app_state_code'
));
```

###### Add params to resource.
```php
$facade = new Soundcloud($clientID, $clientSecret);
$response = $facade->get('/resolve', array(
    'url' => 'http://www.soundcloud.com/hybrid-species'
));
```
or
```php
$facade->get('/resolve');
$facade->setParams(array('url' => 'http://www.soundcloud.com/hybrid-species'));
```

###### Send request
```php
$facade = new Soundcloud($clientID, $clientSecret);
$facade->get('/resolve', array('url' => 'http://www.soundcloud.com/hybrid-species));
$response = $facade->request();
```

###### Authentication with user credentials flow.
```php
$facade = new Soundcloud($clientID, $clientSecret);
// if an access token is returned from soundcloud, it will be automatically
// set for future requests. body response will allways be returned to the client.
$response = $facade->userCredentialsFlow($username, $password);
$facade->get('/me')->request();
```

###### Get CURL last response object
```php
// if you want the CURL response object from last CURL request.
$curlResponse = $facade->getCurlResponse();
```
