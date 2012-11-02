# Soundcloud.com API Wrapper in PHP
Soundcloud API wrapper written just for fun.

If you find issues or have new ideas, feature requests, code optimization, patches, etc. 
Please open an Issue or send a pull request! :) 

## Implemented features 

* Authentication with OAuth2
* Access to all public GET resources
* Access to all private GET resources
* Access to all PUT resources
* Access to all POST resources
* Access to all DELETE resources
* Track Download feature
* oEmbed Access to embed soundcloud player on your page

## TODO

* Code Optimization
* Better Error Handling
* Implementation of future features requests based on users who use this wrapper.  

## Requirements
PHP >= 5.3 with cURL support.
 
## Examples

#### Accessing public resources
```php
$soundcloud = new Soundcloud('CLIENT_ID');

$soundcloud->setResponseType('xml'); // default is json

$response = $soundcloud->getResource('/tracks', array(
                        'q'     => 'House',
                        'order' => 'created_at',
                ));
```

#### Get Authentication URL
```php

// To access private resources you always have to send CLIENT_SECRET and REDIRECT_URI with your
// CLIENT_ID for a successful authentication.
$soundcloud = new Soundcloud('CLIENT_ID', 'CLIENT_SECRET', 'REDIRECT_URI');

$authURL = $test->getAuthUrl();
echo '<a href="' . $authURL . '">Login with Soundcloud</a><br>'; 
```

#### Get Access Token
```php

// Grab pass $_GET['code'] from Soundcloud authorization url to OAuth2 url
// and get a valid token 
$accessToken = $test->getAccessToken($_GET['code']);
    
// Let's set the token so we can request private resources with getResource() method;
$test->setAccessToken($accessToken->access_token);

// Get User private information
$response = $soundcloud->getResource('/me');
        
// lets keep access token in $_SESSION too, or maybe set it to a database table.. what
// ever fits you best.
$_SESSION['oauth_token'] = $accessToken->access_token;
    }
}
```

#### Posting Comments
```php
 
// Let's set the token so we can access 'need-authentication' resources with postResource() method.
$test->setAccessToken($accessToken->access_token);

// 
// Let's post a timed comment. XXXX is track id.
$response = $soundcloud->postResource('/tracks/XXXX/comments', array(
    'comment[body]' => 'Hey Good Track dude!',
    'comment[timestamp]' => 1231314, // NOTICE: timestamp is in milisseconds
));        
```

#### Upload Track
```php

// Let's set the token so we can access 'need-authentication' resources with postResource() method.
$test->setAccessToken($_SESSION['oauth_token']);

/**
 * The process to upload a track is: 
 * 1) user access your web app (on your server) 
 * 2) user upload audio and/or artwork file(s) to your server on your server and only then you can invoke this method
 * 3) with files already uploaded to server, we grab they full local path and invoke postResource()
 */

// Let's upload a track to soundcloud. 
$response = $soundcloud->postResource('/tracks', array(
    'track[title]' => 'Track Name',
    'track[sharing]' => 'public',                           // or 'private'
    'track[asset_data]' => @/path/to/audio/file.wav,        // local path on your server
    'track[artwork_data]' => @/path/to/my/track/image.png,  // local path on your server 
));       
```

#### Download Track
```php
...
 
// Download a track. XXXX is track id.
$soundcloud->download('/tracks/XXXX/download');     
```

#### Get Soundcloud html5 player with oembed
```php
$soundcloud = new Soundcloud('CLIENT_ID');

$response = $soundcloud->getResource('/oembed', array(
                        'url'       => 'http://www.soundcloud.com/cutloosemusic',
                        'color'     => '',      //	(optional) The primary color of the widget as a hex 
                                                //  triplet. (For example: ff0066).
                        'auto_play' => false,   //  (optional) Whether the widget plays on load.
                        'show_comments'=> true, //  (optional) Whether the player displays timed comments.
                        'iframe'    =>  true    //  (optional) Whether the new HTML5 Iframe-based Widget or the old 
                                                //  Adobe Flash Widget will be returned.
                ));

// echo the Soundcloud html5 player on your page with music from url
echo $response->html;
```

#### Follow user
```php
$soundcloud = new Soundcloud('CLIENT_ID', 'CLIENT_SECRET', 'REDIRECT_URI');

// Let's set the token so we can access 'need-authentication' resources with postResource() method.
$test->setAccessToken($_SESSION['oauth_token']);

// XXXXXXXX is user id to follow.
$response = $soundcloud->putResource('/me/followings/XXXXXXXX');
```

#### UnFollow user
```php
$soundcloud = new Soundcloud('CLIENT_ID', 'CLIENT_SECRET', 'REDIRECT_URI');

// Let's set the token so we can access 'need-authentication' resources with postResource() method.
$test->setAccessToken($_SESSION['oauth_token']);

// XXXXXXXX is user id to follow.
$response = $soundcloud->deleteResource('/me/followings/XXXXXXXX');
```