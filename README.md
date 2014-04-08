[![Build Status](https://travis-ci.org/njasm/soundcloud.svg?branch=dev)](https://travis-ci.org/njasm/soundcloud)
## Soundcloud.com API Wrapper in PHP v2.0.0
This DEV branch is still under development.
Design decisions are still being made, so have fun with the master branch TAG 1.0.0. :)


## Implemented features 

* Authentication with OAuth2 (and User Credentials flow)
* Access to all GET, PUT, POST and DELETE Resources

## Examples
### Authentication with user credentials flow.
```php
$facade = new Soundcloud($clientID, $clientSecret);
$facade->userCredentialsFlow($username, $password);
$response = $facade->get('/me')->request();
// if you want the CURL response object
$curlResponse = $facade->getCurlResponse();
```
