# Soundcloud.com API Wrapper
Soundcloud API wrapper written in PHP just for fun.

This piece of code is still in ALPHA.
For now only Authentication over OAuth2 and GET Resources are working.
 
### Requirements
PHP >= 5.3 with cURL support.
 
### Example

```php
$soundcloud = new Soundcloud('CLIENT_ID', 'CLIENT_SECRET', 'REDIRECT_URI');

$soundcloud->setResponseType('xml'); // default is json

$arrayResponse = $soundclooud->getResource('/tracks', array(
                        'q'     => 'House',
                        'order' => 'created_at',
                ));
```