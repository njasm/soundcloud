[![Build Status](https://github.com/njasm/soundcloud/actions/workflows/CI.yaml/badge.svg?branch=master)](https://github.com/njasm/soundcloud) [![Code Coverage](https://scrutinizer-ci.com/g/njasm/soundcloud/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/njasm/soundcloud/?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/njasm/soundcloud/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/njasm/soundcloud/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/njasm/soundcloud/v/stable.png)](https://packagist.org/packages/njasm/soundcloud) [![Total Downloads](https://poser.pugx.org/njasm/soundcloud/downloads.png)](https://packagist.org/packages/njasm/soundcloud) [![License](https://poser.pugx.org/njasm/soundcloud/license.png)](https://packagist.org/packages/njasm/soundcloud) 

## Soundcloud.com API Wrapper in PHP

#### Implemented features 

* User Authorization/Authentication
* User Credentials Flow Authentication
* Access to all GET, PUT, POST and DELETE Resources
* Media File Download/Upload

### Requirements

PHP 5.6 or higher.

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
``SoundcloudFacade.php`` provides you with boilerplate code to get authorization url, change a code for a token, etc etc..

##### Examples
###### Get Authorization Url.
```php
$facade = new SoundcloudFacade($clientID, $clientSecret, $callbackUri);
$url = $facade->getAuthUrl();

// or inject your specific request params
$url = $facade->getAuthUrl(
    [
        'response_type' => 'code',
        'scope' => '*',
        'state' => 'my_app_state_code'
    ]
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

Note: Soundcloud.com stopped sending responses in xml format, the methods are kept in the 2.x.x versions, but calling
them will have no effect on the request, all requests will have an accept header of application/json.

```php
...
$response  = $facade->get('/tracks')->asJson()->request();
// or
$response = $facade->get('/tracks')->asXml()->request();
```

###### Add params to resource.
```php
// argument array style
$facade->get('/resolve', ['url' => 'http://www.soundcloud.com/hybrid-species']);

// chaining-methods
$response = $facade
    ->get('/resolve')
    ->setParams(['url' => 'http://www.soundcloud.com/hybrid-species']);

// or not
$facade->get('/resolve');
$facade->setParams(['url' => 'http://www.soundcloud.com/hybrid-species']);
```

###### Send request
To allow different ways to set the Resource parameters that you are accessing - by submitting an array or 
setParams() method injection. The request will only be sent to soundcloud, when you invoke the request() method.
Take in considerations that specific operations like userCredentials(), download(), etc. will invoke request()
automatically.

```php
$soundcloud = new Soundcloud($clientID, $clientSecret);
$soundcloud->get('/resolve', ['url' => 'http://www.soundcloud.com/hybrid-species']);
// only this invocation will send the request
$response = $soundcloud->request();
```

###### Get the raw response Body
```php
...
$theBodyString = $facade->request()->bodyRaw();
```

###### Create a Playlist / Set and update with tracks
```php
// after having the access token
// build the playlist data array
$playlistData = ['playlist' => ['title' => 'Great Playlist!', 'sharing' => 'public']];
$response = $soundcloud->post('/playlists', $playlistData)->request();

// now add tracks, get playlist id from response
// build tracks array
$tracks = [
    'playlist' => [
        'tracks' => [
            ['id' => 29720509], // track id
            ['id' => 26057359]  // other track id
        ]
    ]
];

// put tracks into playlist
$response = $soundcloud->put('/playlists/' . $response->bodyObject()->id, $tracks)->request();
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
$trackData = [
    'title' => 'Cool track title',
    'downloadable' => true,
    'artwork_data' => new \CURLFile('artwork.jpg'),
    // .... more metadata maybe?
];

$response = $facade->upload($trackPath, $trackData);

// or old-school trackdata array declaration also work, example.
$trackData = [
    'track[title]' => 'Cool track title',
    'track[downloadable]' => true,
    'track[artwork_data]' => new \CURLFile('artwork.jpg'),
    // .... more metadata maybe?
];

$response = $facade->upload($trackPath, $trackData);
```
