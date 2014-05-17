<?php

namespace Njasm\Soundcloud\Request;

interface ResponseInterface
{
    public function getHeaders();
    public function getHeader($header);
    public function hasHeader($header);
    public function bodyString();
    public function bodyObject();
}
