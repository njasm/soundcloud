<?php

use Njasm\Soundcloud\Resources\Resource;
//autoload
include ".." . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";


$clientID = "8427643cbe50e5302f955814f98dccfe";
$clientSecret = "c844cf0f21296d2643a717b34c145556";

$endUserAuthorization = "https://soundcloud.com/connect";
$token = "https://api.soundcloud.com/oauth2/token";


$e = Resource::get("/me");