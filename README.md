# Soundcloud.com API Wrapper in PHP
Soundcloud API wrapper written just for fun.

This piece of code still misses some features.
If you find issues on the implemented ones, new ideas, etc. Please open an Issue. 
Needless to say that code optimization, patches, etc. Are welcome :) 

## Implemented features 

* Authentication with OAuth2
* Access to all public GET resources
* Access to all private GET resources
* Access to all POST resources (insert track comments and track upload)

## TODO

* Access to all PUT resources
* Access to all DELETE resources
* Track Download feature

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
$soundcloud = new Soundcloud('CLIENT_ID', 'CLIENT_SECRET', 'REDIRECT_URI');

$authURL = $test->getAuthUrl();
echo '<a href="' . $authURL . '">Login with Soundcloud</a><br>'; 
```

#### Get Access Token
```php
...
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
...
 
// Let's set the token so we can access 'need-authentication' resources with postResource() method;
$test->setAccessToken($accessToken->access_token);

// 
// Let's post a timed comment. XXXX is track id;
$response = $soundcloud->postResource('/tracks/XXXX/comments', array(
    'comment[body]' => 'Hey Good Track dude!',
    'comment[timestamp]' => 1231314, // NOTICE: timestamp is in milisseconds
));
        
```