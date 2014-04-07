[![Build Status](https://travis-ci.org/njasm/soundcloud.svg?branch=dev)](https://travis-ci.org/njasm/soundcloud)
## Soundcloud.com API Wrapper in PHP v2.0.0
This DEV branch is still under development.
Design decisions are still being made, so have fun with the master branch TAG 1.0.0. :)

## Examples
### Authentication with user credentials flow.
```php
$facade = new Soundcloud($clientID, $clientSecret);
$response = $facade->getTokenViaUserCredentials($username, $password);
$jsonObj = json_decode($response->getBody());
$facade->setAuthToken($jsonObj->access_token);

$response = $facade->get('/me')->request();
echo $response->getBody();
```
