[![Build Status](https://travis-ci.org/njasm/soundcloud.svg?branch=master)](https://travis-ci.org/njasm/soundcloud) [![Coverage Status](https://coveralls.io/repos/njasm/soundcloud/badge.png?branch=master)](https://coveralls.io/r/njasm/soundcloud?branch=master) [![Code Climate](https://codeclimate.com/github/njasm/soundcloud.png)](https://codeclimate.com/github/njasm/soundcloud) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/njasm/soundcloud/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/njasm/soundcloud/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/afcaecc0-c5e8-45ad-b083-5aa1e9a64b51/mini.png)](https://insight.sensiolabs.com/projects/afcaecc0-c5e8-45ad-b083-5aa1e9a64b51)
[![Latest Stable Version](https://poser.pugx.org/njasm/soundcloud/v/stable.png)](https://packagist.org/packages/njasm/soundcloud) [![Total Downloads](https://poser.pugx.org/njasm/soundcloud/downloads.png)](https://packagist.org/packages/njasm/soundcloud) [![License](https://poser.pugx.org/njasm/soundcloud/license.png)](https://packagist.org/packages/njasm/soundcloud) [![HHVM Status](http://hhvm.h4cc.de/badge/njasm/soundcloud.png)](http://hhvm.h4cc.de/package/njasm/soundcloud) 

## Soundcloud.com API Wrapper in PHP

#### Implemented features 

* User Authorization/Authentication
* User Credentials Flow Authentication
* Access to all GET, PUT, POST and DELETE Resources
* Media File Download/Upload

### Requirements

 - PHP 5.3 or higher.

#### Installation

Recommended installation is through composer. 
Include ``njasm\soundcloud`` in your project, by adding it to your ``composer.json`` file.

```javascript
{
    "require": {
        "njasm/soundcloud": "dev-master"
    }
}
```

If you don't use ``composer`` to manage your project dependencies, this library provides your with an 
[autoload.php](https://github.com/njasm/soundcloud/blob/master/src/Soundcloud/autoload.php)
You just need to include [autoload.php](https://github.com/njasm/soundcloud/blob/master/src/Soundcloud/autoload.php) in your project to start using the library as you would if installed through
``composer``.

##### Usage

Include ``Njasm\Soundcloud\`` namespace in the script where you intend to use ``SoundcloudFacade`` or ``Soundcloud`` class.

```php
use Njasm\Soundcloud\SoundcloudFacade;
// or soundcloud if you don't need a facade for specific tasks
use Njasm\Soundcloud\Soundcloud;
```
``SoundcloudFacade.php`` provides your boilerplate code to set get auth url, change a code for a token, etc etc..

##### Examples
###### Get Authorization Url.
```php
$facade = new SoundcloudFacade($clientID, $clientSecret, $callbackUri);
$url = $facade->getAuthUrl();

// or inject your specific request params
$url = $facade->getAuthUrl(
    array(
        'response_type' => 'code',
        'scope' => '*',
        'state' => 'my_app_state_code'
    )
);
```

###### Authentication 
```php
$facade = new SoundcloudFacade($clientID, $clientSecret, $callbackUri);
// this is your callbackUri script that will receive the $_GET['code']
$code = $_GET['code'];
$facade->codeForToken($code); 

```

###### Authentication with user credentials flow.
If an access token is returned from soundcloud, it will be automatically set for future requests. 
The Response object will always be returned to the client.
```php
$facade = new SoundcloudFacade($clientID, $clientSecret);
$facade->userCredentials($username, $password); // on success, access_token is set by default for next requests.
$response = $facade->get('/me')->request();
// raw/string body response
echo $response->bodyRaw();
// as object
echo $response->bodyObject()->id;
// as array
$array = $response->bodyArray();
```

###### Accept response as json or xml
```php
...
$response  = $facade->get('/tracks')->asJson()->request();
// or
$response = $facade->get('/tracks')->asXml()->request();
```

###### Add params to resource.
```php
// argument array style
$facade->get('/resolve', array('url' => 'http://www.soundcloud.com/hybrid-species'));

// chaining-methods
$response = $facade
    ->get('/resolve')
    ->setParams(array('url' => 'http://www.soundcloud.com/hybrid-species'));

// or not
$facade->get('/resolve');
$facade->setParams(array('url' => 'http://www.soundcloud.com/hybrid-species'));
```

###### Send request
To allow different ways to set the Resource parameters that you are accessing - by submitting an array or 
setParams() method injection. The request will only be sent to soundcloud, when you invoke the request() method.
Take in considerations that specific operations like userCredentials(), download(), etc. will invoke request()
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
// this will redirect user, sending a header Location to the track.
$response = $facade->download($trackID);
// redirect user to download URL suplied by soundcloud.
header('Loacation: ' . $response->getHeader('Location'));

// CAUTION: this will get the track into an in-memory variable in your server.
$response = $facade->download($trackID, true);
// save it to a file.
file_put_contents("great_track.mp3", $response->bodyRaw());
```

###### File Upload
```php
$trackPath = '/home/njasm/great.mp3';
$params = array('track[name]' => 'Cool remix');
$response = $facade->upload($trackPath, $params);
```
