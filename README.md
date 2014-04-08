[![Build Status](https://travis-ci.org/njasm/soundcloud.svg?branch=dev)](https://travis-ci.org/njasm/soundcloud)
## Soundcloud.com API Wrapper in PHP
This DEV branch is still under development.
Design decisions are still being made, and still needs further testing.
If you want a stable version, have fun with the master branch TAG 1.0.0. :)

## Implemented features 

* User Credentials flow Authentication
* Access to all GET, PUT, POST and DELETE Resources

## Examples
### Authentication with user credentials flow.
```php
$facade = new Soundcloud($clientID, $clientSecret);
$facade->userCredentialsFlow($username, $password);
// response body string
$response = $facade->get('/me')->request();
// if you want the CURL response object
$curlResponse = $facade->getCurlResponse();
```
