[![Build Status](https://travis-ci.org/njasm/soundcloud.svg?branch=master)](https://travis-ci.org/njasm/soundcloud) [![Coverage Status](https://coveralls.io/repos/njasm/soundcloud/badge.png?branch=master)](https://coveralls.io/r/njasm/soundcloud?branch=master) [![Code Climate](https://codeclimate.com/github/njasm/soundcloud.png)](https://codeclimate.com/github/njasm/soundcloud) [![Latest Stable Version](https://poser.pugx.org/njasm/soundcloud/v/stable.png)](https://packagist.org/packages/njasm/soundcloud) [![Latest Unstable Version](https://poser.pugx.org/njasm/soundcloud/v/unstable.png)](https://packagist.org/packages/njasm/soundcloud) [![HHVM Status](http://hhvm.h4cc.de/badge/njasm/soundcloud.png)](http://hhvm.h4cc.de/package/njasm/soundcloud) [![License](https://poser.pugx.org/njasm/soundcloud/license.png)](https://packagist.org/packages/njasm/soundcloud) [![Dependency Status](https://www.versioneye.com/user/projects/534af6adfe0d078843000029/badge.png)](https://www.versioneye.com/user/projects/534af6adfe0d078843000029) 
### Soundcloud.com API Wrapper in PHP
If you want a stable and complete implementation, have fun with the master TAG 0.0.1 :)

#### Implemented features 

* User Authorization/Authentication
* User Credentials Flow Authentication
* Access to all GET, PUT, POST and DELETE Resources
* Media File Download

#### Todo

* Media File Upload

##### Examples
###### Get Authorization Url.
```php
$facade = new Soundcloud($clientID, $clientSecret, $callbackUri);
$url = $facade->getAuthUrl();

// or inject your speciefic request params
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
$code = $_GET['code'];
$facade->codeForToken($code); 

```

###### Authentication with user credentials flow.
```php
$facade = new Soundcloud($clientID, $clientSecret);
// If an access token is returned from soundcloud, it will be automatically set for future requests. 
// The Response object will always be returned to the client.
$facade->userCredentials($username, $password);
$response = $facade->get('/me')->request();
// raw body response
echo $response->bodyRaw();
```

###### Add params to resource.
```php
// argument array style
$facade->get('/resolve', array(
    'url' => 'http://www.soundcloud.com/hybrid-species'
));

// chaining-methods
$response = $facade->get('/resolve')
    ->setParams(array('url' => 'http://www.soundcloud.com/hybrid-species'));

// or not
$facade->get('/resolve');
$facade->setParams(array('url' => 'http://www.soundcloud.com/hybrid-species'));
```

###### Send request
To allow different ways to inject the Resource parameters that you are accessing - by injection an array or 
setParams() method injection. The request will only be sent to soundcloud, when you invoke the request() method.
Take in considerations that specific operations like userCredentials(), download($trackID), etc. will invoke request()
automatically.

```php
$facade = new Soundcloud($clientID, $clientSecret);
$facade->get('/resolve', array('url' => 'http://www.soundcloud.com/hybrid-species'));
// only this invocation will send the request
$response = $facade->request();
```

###### Get the raw response Body
```php
...
$theBodyString = $facade->request()->bodyRaw();
```

###### Get CURL last response object
```php
// if you want the CURL response object from last CURL request.
$response = $facade->getCurlResponse();
```

###### File Download
```php
//this will redirect user, sending a header Location to the track.
$facade->download($trackID);

// CAUTION: this will get the track into an in-memory variable in your server.
$response = $facade->download($trackID, false);
// save it to a file.
file_put_contents("great_track.mp3", $response->bodyRaw());
```
