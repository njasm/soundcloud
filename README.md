[![Build Status](https://travis-ci.org/njasm/soundcloud.svg?branch=master)](https://travis-ci.org/njasm/soundcloud) [![Coverage Status](https://coveralls.io/repos/njasm/soundcloud/badge.png?branch=master)](https://coveralls.io/r/njasm/soundcloud?branch=master) [![Latest Stable Version](https://poser.pugx.org/njasm/soundcloud/v/stable.png)](https://packagist.org/packages/njasm/soundcloud) [![Latest Unstable Version](https://poser.pugx.org/njasm/soundcloud/v/unstable.png)](https://packagist.org/packages/njasm/soundcloud) [![License](https://poser.pugx.org/njasm/soundcloud/license.png)](https://packagist.org/packages/njasm/soundcloud) [![Dependency Status](https://www.versioneye.com/user/projects/534af6adfe0d078843000029/badge.png)](https://www.versioneye.com/user/projects/534af6adfe0d078843000029) 
### Soundcloud.com API Wrapper in PHP
If you want a stable and complete implementation, have fun with the master TAG 0.0.1 :)

#### Implemented features 

* User Authorization/Authentication
* User Credentials Flow Authentication
* Access to all GET, PUT, POST and DELETE Resources

#### Todo

* Media File Download
* Media File Upload

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

###### Authentication 
```php
$facade = new Soundcloud($clientID, $clientSecret, $callbackUri);
// this is your callbackUri script that will receive the $_GET['code']
// example only, you should always sanitize your submitted params
$code = $_GET['code'];
$facade->codeForToken($code);
$response = $facade->get('/me')->request();
```

###### Authentication with user credentials flow.
```php
$facade = new Soundcloud($clientID, $clientSecret);
// if an access token is returned from soundcloud, it will be automatically
// set for future requests by the Response object will always be returned to the client.
$facade->userCredentialsFlow($username, $password);
$response = $facade->get('/me')->request();
// raw body response
echo $response->getBody();
```

###### Add params to resource.
```php
// argument array style
$facade->get('/resolve', array(
    'url' => 'http://www.soundcloud.com/hybrid-species'
));

// chaining-methods
$response = $facade->get('/resolve')
    ->setParams(array('url' => 'http://www.soundcloud.com/hybrid-species'))

$facade->get('/resolve');
$facade->setParams(array('url' => 'http://www.soundcloud.com/hybrid-species'));
```

###### Send request
```php
$facade = new Soundcloud($clientID, $clientSecret);
$facade->get('/resolve', array('url' => 'http://www.soundcloud.com/hybrid-species'));
$response = $facade->request();
```

###### Get raw Response Body
```php
...
$response = $facade->request();
$response->getBody();
```

###### Get CURL last response object
```php
// if you want the CURL response object from last CURL request.
$curlResponse = $facade->getCurlResponse();
```
