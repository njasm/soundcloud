[![Build Status](https://travis-ci.org/njasm/soundcloud.svg?branch=master)](https://travis-ci.org/njasm/soundcloud)[![Dependency Status](https://www.versioneye.com/user/projects/534af6adfe0d078843000029/badge.png)](https://www.versioneye.com/user/projects/534af6adfe0d078843000029)
## Soundcloud.com API Wrapper in PHP
This is still under development.
Design decisions are still being made, and still needs further testing.
If you want a stable version, have fun with the master TAG 0.0.1 :)

## v1.0.0-ALPHA
### Implemented features 

* User Authorization/Authentication
* User Credentials Flow Authentication
* Access to all GET, PUT, POST and DELETE Resources

### Examples
#### Get Authorization Url.
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
#### Add params to resource.
```php
$facade = new Soundcloud($clientID, $clientSecret);
// like this
$response = $facade->get('/resolve', array(
    'url' => 'http://www.soundcloud.com/hybrid-species'
))->request();
// or
$facade->get('/resolve');
$facade->setParams(array('url' => 'http://www.soundcloud.com/hybrid-species'));
$response = $facade->request();
```

#### Authentication with user credentials flow.
```php
$facade = new Soundcloud($clientID, $clientSecret);
$facade->userCredentialsFlow($username, $password);
// response body string
$response = $facade->get('/me')->request();
// if you want the CURL response object
$curlResponse = $facade->getCurlResponse();
```
